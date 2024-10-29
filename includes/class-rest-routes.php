<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class ARIFIX_AP_Rest_Routes
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'arifix_ap_create_rest_routes']);
    }

    public function arifix_ap_create_rest_routes()
    {
        register_rest_route('arifix-ap-/v1', '/settings', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_get_settings'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/settings', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_save_settings'],
            'permission_callback' => [$this, 'arifix_ap_save_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/settings/reset', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_reset_settings'],
            'permission_callback' => [$this, 'arifix_ap_save_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/settings/backup', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_backup_settings'],
            'permission_callback' => [$this, 'arifix_ap_save_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/settings/restore', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_restore_settings'],
            'permission_callback' => [$this, 'arifix_ap_save_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/post-types', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_ap_get_post_types'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/authors', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_ap_get_authors'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/taxonomies', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_ap_get_taxonomies'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/terms', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_ap_get_terms'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/posts', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_ap_get_posts'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/categories', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_get_categories'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/grid/all', [
            'methods' => 'GET',
            'callback' => [$this, 'arifix_ap_get_grid_all'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/grid/get', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_get_grid_single'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/grid/new', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_create_grid'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);

        register_rest_route('arifix-ap-/v1', '/grid/delete', [
            'methods' => 'POST',
            'callback' => [$this, 'arifix_ap_delete_grid'],
            'permission_callback' => [$this, 'arifix_ap_get_settings_permission']
        ]);
    }

    public function arifix_ap_get_settings()
    {
        $settings = get_option('arifix_ap_grid_settings');
        $response = [
            'settings' => $settings
        ];

        return rest_ensure_response($response);
    }

    public function arifix_ap_get_settings_permission()
    {
        return true;
    }

    public function arifix_ap_save_settings($req)
    {
        if (is_string($req)) {
            $data =  json_decode($req);
            $settings =  wp_json_encode($data->settings);
        } else {
            $settings = sanitize_text_field($req['settings']);
        }

        $data = $this->arifix_ap_get_settings();
        if ($data->data['settings']) {
            $existing_setting = $data->data['settings'];
            $new_settings =  wp_json_encode(array_merge(json_decode($existing_setting, true),  json_decode($settings, true)));
        } else {
            $new_settings = $settings;
        }

        update_option('arifix_ap_grid_settings', $new_settings);

        return ['message' => 'Setting Saved Successfully'];
    }

    public function arifix_ap_reset_settings($req)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $wpdb->query($wpdb->prepare("TRUNCATE TABLE %i", $table_name));

        update_option('arifix_ap_grid_settings', "");

        return ['message' => 'Setting Reset Successfully'];
    }

    public function arifix_ap_backup_settings()
    {
        global $wpdb, $wp_filesystem;
        include_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();

        $settings = get_option('arifix_ap_grid_settings');
        $setting_obj = json_decode($settings);

        $fonts = [];
        if (isset($setting_obj->fonts)) {
            foreach ($setting_obj->fonts as $key => $url) {
                if (strlen($key) > 10) {
                    $fonts[$key] = $url;
                }
            }

            $setting_obj->fonts = $fonts;
        }

        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM %i WHERE 1", $table_name));

        $grids = [];
        if (count($results) > 0) {
            foreach ($results as $res) {
                $grids[$res->title] = $res->settings;
            }
        }

        $setting_obj->grids =  wp_json_encode($grids);

        $upload_dir = wp_get_upload_dir();
        $file_name = 'awesome_posts_backup.json';
        $upload_url = $upload_dir['basedir'] . '/' . $file_name;
        $wp_filesystem->put_contents($upload_url,  wp_json_encode($setting_obj));

        $file_url = $upload_dir['baseurl'] . '/' . $file_name;
        return ['message' => 'Setting Reset Successfully', 'file_name' => $file_name, 'file_url' => $file_url];
    }

    public function arifix_ap_restore_settings($req)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $file_id = sanitize_text_field($req['file_id']);
        $json_url = sanitize_text_field($req['json_url']);
        $request = wp_remote_get($json_url);
        $new_settings =  wp_remote_retrieve_body($request);

        $settings_obj =  json_decode($new_settings);
        $grids = $settings_obj->grids;
        unset($settings_obj->grids);

        $grid_obj =  json_decode($grids);
        if (count((array) $grid_obj) > 0) {
            $wpdb->query($wpdb->prepare("TRUNCATE TABLE %i", $table_name));

            foreach ($grid_obj as $key => $val) {
                $wpdb->insert($table_name, array(
                    'title' => $key,
                    'settings' => $val,
                    'timestamp' => time(),
                ));
            }
        }

        update_option('arifix_ap_grid_settings',  wp_json_encode($settings_obj));

        wp_delete_attachment($file_id);
        return ['message' => 'Setting Restored Successfully'];
    }

    public function arifix_ap_ap_get_post_types()
    {
        $args = array(
            'public'   => true,
            '_builtin' => false,
        );

        $post_types = get_post_types($args, 'names', 'and');

        $types = [['value' => 'post', 'label' => 'Post']];
        if (count($post_types) > 0) {
            foreach ($post_types as $pt) {
                if ($pt == 'product') {
                    continue;
                }

                $types[] = ['value' => $pt, 'label' => ucwords(str_replace(array('-', '_'), ' ', $pt))];
            }
        }

        return $types;
    }

    public function arifix_ap_ap_get_authors()
    {
        $users = get_users();

        $authors = [];
        foreach ($users as $user) {
            $authors[] = ['value' => $user->ID, 'label' => ucwords($user->display_name)];
        }

        return $authors;
    }

    public function arifix_ap_ap_get_taxonomies()
    {

        if (wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'wp_rest') && $_GET['post-type']) {
            $taxonomies = get_object_taxonomies(sanitize_text_field($_GET['post-type']), 'names');
            $taxs = [];
            if (count($taxonomies) > 0) {
                foreach ($taxonomies as $tax) {
                    if ($tax == 'post_tag' || $tax == 'post_format') {
                        continue;
                    }
                    $taxs[] = ['value' => $tax, 'label' => ucwords(str_replace(array('-', '_'), ' ', $tax))];
                }
            }

            return $taxs;
        }

        return [];
    }

    public function arifix_ap_ap_get_terms()
    {
        if (wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'wp_rest') && $_GET['post-type'] && $_GET['taxonomy']) {
            $args = array(
                'post_type' => sanitize_text_field($_GET['post-type']),
                'taxonomy'  => sanitize_text_field($_GET['taxonomy'])
            );
            $terms = get_terms($args);

            $cats = [];
            if (count($terms) > 0) {
                foreach ($terms as $t) {
                    $cats[] = ['value' => $t->slug, 'label' => $t->name];
                }
            }

            return $cats;
        }

        return [];
    }

    public function arifix_ap_ap_get_posts()
    {
        if (wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'wp_rest')) {
            $terms = !empty($_GET['terms']) ? explode(",", sanitize_text_field($_GET['terms'])) : [];
            $post_status = !empty($_GET['post_status']) ? explode(",", sanitize_text_field($_GET['post_status'])) : [];
            $authors = !empty($_GET['authors']) ? sanitize_text_field($_GET['authors']) : "";

            $posts_args = array(
                'post_type' => sanitize_text_field($_GET['post-type']),
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
            );

            if (!empty($_GET['taxonomy']) && count($terms) > 0) {
                $tax_query = [];
                $tax_query['relation'] = sanitize_text_field($_GET['relation']);

                foreach ($terms as $term) {
                    array_push(
                        $tax_query,
                        array(
                            'taxonomy' => sanitize_text_field($_GET['taxonomy']),
                            'field' => 'slug',
                            'terms' => $term,
                            'operator' => sanitize_text_field($_GET['operator'])
                        )
                    );
                }

                if (count($tax_query) > 1) {
                    $posts_args['tax_query'] = $tax_query;
                }
            }

            if (!empty($_GET['orderby'])) {
                $posts_args['orderby'] = sanitize_text_field($_GET['orderby']);
            }

            if (!empty($_GET['order'])) {
                $posts_args['order'] = sanitize_text_field($_GET['order']);
            }

            if (!empty($_GET['startDate'])) {
                $posts_args['date_query'] = array(
                    array(
                        'after' => sanitize_text_field($_GET['startDate']),
                        'before' => !empty($_GET['endDate']) ? sanitize_text_field($_GET['endDate']) : gmdate('Y-m-d'),
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

            if (!empty($_GET['search'])) {
                $posts_args['s'] = sanitize_text_field($_GET['search']);
            }

            $posts_query = new WP_Query($posts_args);

            $posts = [];
            if ($posts_query->have_posts()) {
                while ($posts_query->have_posts()) {
                    $posts_query->the_post();

                    $posts[] = ['value' => get_the_ID(), 'label' => get_the_title()];
                }
            }

            return $posts;
        }

        return [];
    }

    public function arifix_ap_save_settings_permission()
    {
        return current_user_can('manage_options');
    }

    public function arifix_ap_get_categories()
    {
        $pro_args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'show_count' => 1,
            'hierarchical' => 1
        );

        $pro_categories = get_categories($pro_args);

        $pro_cats = [];
        if (count($pro_categories) > 0) {
            foreach ($pro_categories as $cat) {
                $pro_cats[] = ['value' => $cat->slug, 'label' => $cat->name];
            }
        }

        return $pro_cats;
    }

    public function arifix_ap_get_grid_all()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM %i WHERE 1", $table_name));

        return $results;
    }

    public function arifix_ap_get_grid_single($req)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $grid_id = $req['grid_id'];
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM %i WHERE `id`= %d", $table_name, $grid_id));

        return $results[0];
    }

    public function arifix_ap_create_grid($req)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $grid_id = $req['grid_id'];
        $title = $req['grid_title'];
        $settings = $req['grid_settings'];

        if ($grid_id) {
            $wpdb->update(
                $table_name,
                array(
                    'title' => $title,
                    'settings' => $settings,
                ),
                array(
                    "id" => $grid_id
                )
            );
            $message = 'Grid Updated Successfully';
        } else {
            $wpdb->insert($table_name, array(
                'title' => $title,
                'settings' => $settings,
                'timestamp' => time(),
            ));

            $grid_id = $wpdb->insert_id;
            $message = 'Grid Created Successfully';
        }

        return ['grid_id' => $grid_id, 'message' => $message];
    }

    public function arifix_ap_delete_grid($req)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;
        $wpdb->delete($table_name, array('id' => $req['del_id']));

        return ['message' => 'Grid Deleted Successfully'];
    }
}

new ARIFIX_AP_Rest_Routes();
