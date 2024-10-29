<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class ARIFIX_AP_Shortcodes
{
    public function __construct()
    {
        add_shortcode('awesome-posts', [$this, 'arifix_ap_posts']);
        add_action('wp_ajax_get_awesome-posts', [$this, 'arifix_ap_posts_ajax']);
        add_action('wp_ajax_nopriv_get_awesome-posts', [$this, 'arifix_ap_posts_ajax']);
    }

    function arifix_ap_fonts_url_to_name($font_url)
    {
        $font_url_ar = explode("/", $font_url);
        $font_name = explode(".", $font_url_ar[count($font_url_ar) - 1]);
        return $font_name[0];
    }

    function arifix_ap_posts($atts)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $atts = shortcode_atts(array(
            'id' => ""
        ), $atts, 'awesome-posts');

        $id = $atts['id'];

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM %i WHERE id = %d", $table_name, $id));

        $styles = '';

        if ($result !== null) {
            $grid_title = $result->title;
            $settings = $result->settings;
            $set = json_decode($settings);

            $post_type = $set->postType->value;
            $post_per_page = $set->postsPerPage ?: -1;
            $offset = $set->postOffset ?: 0;
            $taxonomy = !empty($set->taxonomy) ? $set->taxonomy->value : "";
            $terms = [];
            if (count((array) $set->terms) > 0) {
                $tr = $set->terms;

                foreach ($tr as $t) {
                    $terms[] = $t->value;
                }
            }

            $relation = $set->relation;
            $operator = $set->operator;
            $post_orderby = $set->postOrderBy;
            $post_order = $set->postOrder;
            $start_date = !empty($set->postStartDate) ? explode('T', $set->postStartDate[0])[0] : "";
            $end_date = !empty($set->postEndDate) ? explode('T', $set->postEndDate[0])[0] : "";

            $post_status = [];
            if (count((array) $set->postStatus) > 0) {
                $ps = $set->postStatus;

                foreach ($ps as $p) {
                    $post_status[] = $p->value;
                }
            }

            $authors = [];
            if (count((array) $set->authors) > 0) {
                $aut = $set->authors;

                foreach ($aut as $a) {
                    $authors[] = $a->value;
                }
            }

            $search = $set->search;

            $posts_include = [];
            if (count((array) $set->postsToInclude) > 0) {
                $pi = $set->postsToInclude;

                foreach ($pi as $p) {
                    $posts_include[] = $p->value;
                }
            }

            $posts_exclude = [];
            if (count((array) $set->postsToExclude) > 0) {
                $pe = $set->postsToExclude;

                foreach ($pe as $p) {
                    $posts_exclude[] = $p->value;
                }
            }

            $posts_args = array(
                'post_type' => $post_type,
                'posts_per_page' => $post_per_page,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
                'offset' => $offset
            );

            if (!empty($taxonomy) && count($terms) > 0) {
                $tax_query = [];
                $tax_query['relation'] = $relation;

                foreach ($terms as $term) {
                    array_push(
                        $tax_query,
                        array(
                            'taxonomy' => $taxonomy,
                            'field' => 'slug',
                            'terms' => $term,
                            'operator' => $operator
                        )
                    );
                }

                if (count($tax_query) > 1) {
                    $posts_args['tax_query'] = $tax_query;
                }
            }

            if (!empty($post_orderby)) {
                $posts_args['orderby'] = $post_orderby;
            }

            if (!empty($post_order)) {
                $posts_args['order'] = $post_order;
            }

            if (!empty($start_date)) {
                $posts_args['date_query'] = array(
                    array(
                        'after' => $start_date,
                        'before' => !empty($end_date) ? $end_date : gmdate('Y-m-d'),
                        'inclusive' => true,
                    )
                );
            }

            if (count($post_status) > 0) {
                $posts_args['post_status'] = $post_status;
            }

            if (!empty($authors)) {
                $posts_args['author'] = $authors;
            }

            if (!empty($search)) {
                $posts_args['s'] = $search;
            }

            if (count($posts_include) > 0) {
                $posts_args['post__in'] = $posts_include;
            }

            if (count($posts_exclude) > 0 && count($posts_include) == 0) {
                $posts_args['post__not_in'] = $posts_exclude;
            }

            $posts_query = new WP_Query($posts_args);
            $found_posts =  $posts_query->found_posts;
            $total_posts =  $found_posts - $offset;

            $html = '';

            $styles = require ARIFIX_AP_PATH . 'templates/grid-styles.php';

            if ($posts_query->have_posts()) {
                $html .= '<div class="arifix-ap-wrapper arifix-ap-grid-' . $set->gridStyle . ' afx-grid-' . $id . '">';
                $html .= '<div class="ap-loader">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>';

                if ($set->displaySCHeading) {
                    $html .= '<' . strtolower($set->scHeadingTag) . ' class="ap-grid-title">' . $grid_title . '</' . strtolower($set->scHeadingTag) . '>';
                }

                $html .= '<div class="arifix-ap-posts">';
                while ($posts_query->have_posts()) {
                    $posts_query->the_post();
                    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                    $author = get_the_author();
                    $comments = get_comment_count(get_the_ID());

                    $cats = [];
                    if ($post_type == 'post') {
                        $term_list = wp_get_post_terms(get_the_ID(), 'category', array('fields' => 'all'));

                        foreach ($term_list as $term) {
                            $term_link = get_term_link($term);
                            $cats[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
                        }
                    } else {
                        if (!empty($taxonomy)) {
                            $term_list = wp_get_post_terms(get_the_ID(), $taxonomy, array('fields' => 'all'));

                            foreach ($term_list as $term) {
                                $term_link = get_term_link($term);
                                $cats[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
                            }
                        }
                    }

                    $grid_layout = require ARIFIX_AP_PATH . 'templates/grid-' . $set->gridStyle . '.php';
                    $html .= $grid_layout;
                }
                $html .= '</div>';

                if ($set->loadMoreBtn) {
                    if ($post_per_page <= $total_posts) {
                        $html .= '<p style="text-align: center;">
                            <button type="button" 
                                class="ap-more-btn" 
                                data-wp-nonce="' . wp_create_nonce('ajax_nonce') . '" 
                                data-admin-ajax="' . admin_url("admin-ajax.php") . '" 
                                data-query="' . str_replace('"', '\'', wp_json_encode($posts_args)) . '" 
                                data-settings="' .  str_replace('"', '\'', wp_json_encode($set)) . '" 
                                data-query-offset="' . $offset . '" 
                                data-total-posts="' . $total_posts . '">' . $set->loadMoreBtnText . '
                            </button>
                        </p>';
                    }
                }
                $html .= '</div>';
            }

            wp_register_style('arifix-ap-frontend-inline-style', false);
            wp_enqueue_style('arifix-ap-frontend-inline-style');
            wp_add_inline_style('arifix-ap-frontend-inline-style', $styles);

            wp_reset_query();
            wp_reset_postdata();

            return wp_kses_post($html);
        }

        return "";
    }

    function arifix_ap_posts_ajax()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_wpnonce']), 'ajax_nonce')) {
            $posts_args = !empty($_REQUEST['query']) ? json_decode(str_replace("\'", "\"", sanitize_text_field($_REQUEST['query'])), true) : [];
            $posts_args['offset'] = isset($_REQUEST['offset']) ? $posts_args->offset + sanitize_text_field($_REQUEST['offset']) : 0;
            $set = !empty($_REQUEST['settings']) ? json_decode(str_replace("\'", "\"", sanitize_text_field($_REQUEST['settings']))) : [];

            $posts_query = new WP_Query($posts_args);

            $taxonomy = isset($posts_args['tax_query']) ? $posts_args['tax_query'][0]['taxonomy'] : '';

            $html = '';
            if ($posts_query->have_posts()) {
                while ($posts_query->have_posts()) {
                    $posts_query->the_post();
                    $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');

                    $author = get_the_author();
                    $comments = get_comment_count(get_the_ID());

                    $cats = [];
                    if ($post_type == 'post') {
                        $term_list = wp_get_post_terms(get_the_ID(), 'category', array('fields' => 'all'));

                        foreach ($term_list as $term) {
                            $term_link = get_term_link($term);
                            $cats[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
                        }
                    } else {
                        if (!empty($taxonomy)) {
                            $term_list = wp_get_post_terms(get_the_ID(), $taxonomy, array('fields' => 'all'));

                            foreach ($term_list as $term) {
                                $term_link = get_term_link($term);
                                $cats[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
                            }
                        }
                    }

                    $grid_ajax = require ARIFIX_AP_PATH . 'templates/grid-' . $set->gridStyle . '.php';
                    $html .= $grid_ajax;
                }
            }

            echo wp_json_encode([
                'result' => wp_kses_post($html),
            ]);

            wp_reset_query();
            wp_reset_postdata();
        }
        echo "";

        die();
    }
}

new ARIFIX_AP_Shortcodes();
