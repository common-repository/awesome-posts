<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly 

class ARIFIX_AP_Activator
{

    public static function activate()
    {
        self::create_db();
    }

    public static function create_db()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . ARIFIX_AP_TABLE_NAME;
        $arifix_ap_db_version = get_option('arifix_ap_db_version', '1.0');

        if (
            $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) != $table_name ||
            version_compare($arifix_ap_db_version, '1.0') < 0
        ) {

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                `id` int(11) NOT NULL AUTO_INCREMENT,
               `title` varchar(200) NOT NULL,
               `settings` text NOT NULL,
               `timestamp` varchar(20) NOT NULL,
               PRIMARY KEY (id)
           ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            /**
             * It seems IF NOT EXISTS isn't needed if you're using dbDelta - if the table already exists it'll
             * compare the schema and update it instead of overwriting the whole table.
             *
             * @link https://code.tutsplus.com/tutorials/custom-database-tables-maintaining-the-database--wp-28455
             */
            dbDelta($sql);

            add_option('arifix_ap_db_version', $arifix_ap_db_version);
        }
    }
}
