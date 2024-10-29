<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class ARIFIX_AP_Enqueues
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'arifix_ap_admin_enqueue']);
        add_action('wp_enqueue_scripts', [$this, 'arifix_ap_frontend_enqueue']);
    }

    function arifix_ap_admin_enqueue($hook)
    {
        if ($hook == 'toplevel_page_awesome-posts') {
            wp_enqueue_script('arifix-ap-admin-build', plugins_url() . '/awesome-posts/build/index.js', ['jquery', 'wp-element'], wp_rand(), true);
            wp_localize_script('arifix-ap-admin-build', 'afxApApp', [
                'apiUrl' => home_url('/wp-json'),
                'nonce' => wp_create_nonce('wp_rest'),
            ]);

            wp_enqueue_style('arifix-ap-admin-style', plugins_url() . '/awesome-posts/build/index.css', [], '1.0.0');
        }
    }

    function arifix_ap_frontend_enqueue()
    {
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'awesome-posts')) {
            wp_enqueue_script('arifix-ap-frontend-script', plugins_url() . '/awesome-posts/assets/js/script.js', ['jquery'], '1.0.0', true);
            wp_enqueue_style('arifix-ap-frontend-style', plugins_url() . '/awesome-posts/assets/css/style.css', [], '1.0.0');
        }
    }
}

new ARIFIX_AP_Enqueues();
