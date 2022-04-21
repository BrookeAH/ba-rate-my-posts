<?php

/**
 * Fired during plugin activation
 *
 * @link       whatbrookesees.com\ba-rate-my-posts
 * @since      1.0.0
 *
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation. 
 * ( add_options, create database tables )
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */

namespace Ba_Rate_My_Posts;

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class Ba_Rate_My_Posts_Activator {

    const added_options = array(
        'ba_rate_my_posts_icon',
        'ba_rate_my_posts_version',
        'ba_rate_my_posts_db_version',
    );

	/**
	 * Calls all functions necessary for activation.
	 *
	 * This is where we: add_options and create database tables for plugin to work.
	 *
	 * @since    1.0.0
	 */
    public static function activate( ) {
        // create tables
        Self::ba_rate_my_posts_create_table();
        // add_options
        Self::ba_rate_my_post_add_options();
        // flush rules
        flush_rewrite_rules();
    }

    /**
     * Creates table - ba_rate_my_posts_ratings table
     * Creates wp_option - ba_rate_my_posts_db_version
     * @since    1.0.0
     */
    public static function ba_rate_my_posts_create_table( ){
        global $wpdb;
        $table_name = $wpdb->prefix . 'ba_rate_my_posts_ratings';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `post_id` VARCHAR(50) NOT NULL,
                    `rating` FLOAT NULL DEFAULT NULL,
                    `user_id` INT(11) NOT NULL,
                    PRIMARY KEY (`id`)
                )
                $charset_collate
                ENGINE=INNODB
                ;
        ";

       $table = dbDelta( $sql );
    }

    /**
     * Creates wp_option for all options
     * !Add all options to const added_options after you have added it here!
     * Sets default as star-filled
     * @since    1.0.0
     */
    public static function ba_rate_my_post_add_options(){

        if( !get_option('ba_rate_my_posts_icon') ){
            $add = add_option( "ba_rate_my_posts_icon", Ba_Rate_My_Posts::default_icon );
        }

        if( !get_option('ba_rate_my_posts_version') ){
            $add = add_option( "ba_rate_my_posts_version", BA_RATE_MY_POSTS_VERSION );
        }

        if( !get_option('ba_rate_my_posts_db_version') ){
            $add = add_option( "ba_rate_my_posts_db_version", BA_RATE_MY_POSTS_DB_VERSION );
        }
    }
}
