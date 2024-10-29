<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class ARIFIX_AP_Deactivator
{
    public static function deactivate()
    {
        self::delete_db();
    }

    public static function delete_db()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;

        $wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS %i", $table_name));
        delete_option("arifix_ap_db_version");
        delete_option("arifix_ap_grid_settings");
    }
}
