<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       whatbrookesees.com\ba-rate-my-posts
 * @since      1.0.0
 *
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/public
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */
namespace Ba_Rate_My_Posts;

class Ba_Rate_My_Posts_Public {

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_styles() {

		wp_enqueue_style( Ba_Rate_My_Posts::plugin_name, plugin_dir_url( __FILE__ ) . '../assets/css/ba-rate-my-posts-public.css', array(), Ba_Rate_My_Posts::plugin_version, 'all' );
        wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_scripts() {

        global $post;

		wp_enqueue_script( Ba_Rate_My_Posts::plugin_name, plugin_dir_url( __FILE__ ) . '../assets/js/ba-rate-my-posts-public.js', array( 'jquery' ), Ba_Rate_My_Posts::plugin_version, false );
        
		wp_localize_script( Ba_Rate_My_Posts::plugin_name, 'ba_rate_my_posts_vars', array(
            'postID' => $post->ID,
            'user_logged_in' => is_user_logged_in(),
        ) );

        wp_localize_script( Ba_Rate_My_Posts::plugin_name , 'wpApiSettings', array(
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
        ) );
	}

    /**
     * includes files necessary for shortcode tag ba-rate-my-posts-rating
     * includes user_rating - user-logged-in-files - only if user is logged in
     * @since      1.0.0
     * @return false|string
     */
    public static function register_shortcode_ba_rate_my_posts_rating(){
        ob_start();

        include( plugin_dir_path( __FILE__ ) . '../assets/views/ba-rate-my-posts-public-display.php' );
        return ob_get_clean();
    }

}
