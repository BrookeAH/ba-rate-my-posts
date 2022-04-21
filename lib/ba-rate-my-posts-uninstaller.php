<?php

/**
 * Fired during plugin deactivation
 *
 * @link       whatbrookesees.com\ba-rate-my-posts
 * @since      1.0.0
 *
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 */

/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */

namespace Ba_Rate_My_Posts;

class Ba_Rate_My_Posts_Uninstaller {


    /**
     * Calls all functions necessary for uninstallation
     *
     * @since    1.0.0
     */
    public static function uninstall() {
        Self::drop_ba_rate_my_posts_table();
        Self::delete_ba_rate_my_posts_options();
    }

    /**
     * Drops all tables created in plugin activation
     * @since    1.0.0
     */
    public static function drop_ba_rate_my_posts_table(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'ba_rate_my_posts_ratings';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    /**
     * Deletes all options previously set in plugin activation
     * @since    1.0.0
     */
    public static function delete_ba_rate_my_posts_options(){

        $options = Ba_Rate_My_Posts_Activator::added_options;

        foreach( $options as $option_name){
            delete_option( $option_name );
        }

    }

}
