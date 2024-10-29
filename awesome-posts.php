<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

/**
 *
 * Plugin Name: Awesome Posts
 * Plugin URI: https://madebyarif.com/app/awesome-posts
 * Description: Transform Your Posts with Style - Your Ultimate WordPress Plugin for Showcasing Posts in a Grid Layout!
 * Author: Arif Khan
 * Author URI: https://www.arif-khan.net/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.0.1
 * Text Domain: awesome-posts
 * Requires at least: 6.3
 * Requires PHP: 7.4
 *
 */


define('ARIFIX_AP_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('ARIFIX_AP_TABLE_NAME', 'arifix_ap_data');

require_once ARIFIX_AP_PATH . 'includes/class-rest-routes.php';
require_once ARIFIX_AP_PATH . 'includes/class-admin-menu.php';
require_once ARIFIX_AP_PATH . 'includes/class-enqueues.php';
require_once ARIFIX_AP_PATH . 'includes/class-shortcodes.php';
require_once ARIFIX_AP_PATH . 'includes/class-helper.php';

register_activation_hook(__FILE__, 'arifix_ap_activate_plugin');
function arifix_ap_activate_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-plugin-activator.php';
    ARIFIX_AP_Activator::activate();
}

register_uninstall_hook(__FILE__, 'arifix_ap_delete_plugin');
function arifix_ap_delete_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-plugin-deactivator.php';
    ARIFIX_AP_Deactivator::deactivate();
}
