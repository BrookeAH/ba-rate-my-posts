<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       whatbrookesees.com\ba-rate-my-posts
 * @since      1.0.0
 *
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */
namespace Ba_Rate_My_Posts;

require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-activator.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-deactivator.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-uninstaller.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-i18n.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-admin.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-public.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/lib/ba-rate-my-posts-rest-controller.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . '/models/ba-rate-my-posts-ratings-model.php';

class Ba_Rate_My_Posts{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    const plugin_name = 'ba-rate-my-posts';

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    const plugin_version = '1.0.0';

    /**
     * Icon options for ratings
     * Easily ties into dashicons, some dashicons names have been changed for ease of use
     * @since    1.0.0
     * @var array
     */
    const icon_options = [
        'star-filled',
        'heart',
        'carrot',
        'paw',
        'diamond',
    ];


    /**
     * Default icon
     * @since    1.0.0
     * @var array
     */
    const default_icon = Self::icon_options[0];

    public static function install(){
        /**
         * The code that runs during plugin activation.
         * This action is documented in lib/ba-rate-my-posts-activator.php
         */
        register_activation_hook( BA_RATE_MY_POSTS_PLUGIN_FILE,  __NAMESPACE__ . '\Ba_Rate_My_Posts_Activator::activate' );
       
        /**
         * The code that runs during plugin deactivation.
         * This action is documented in lib/ba-rate-my-posts-deactivator.php
         */
        register_deactivation_hook( BA_RATE_MY_POSTS_PLUGIN_FILE,  __NAMESPACE__ . '\Ba_Rate_My_Posts_Deactivator::deactivate' );


        /**
         * The code that runs during plugin delete/uninstall
         * This action is documented in lib/ba-rate-my-posts-uninstaller.php
         */
        register_uninstall_hook(BA_RATE_MY_POSTS_PLUGIN_FILE,  __NAMESPACE__ . '\Ba_Rate_My_Posts_Uninstaller::uninstall');


    }

    public static function run(){

        // admin
        add_action( 'plugins_loaded' , __NAMESPACE__ . '\Ba_Rate_My_Posts_i18n::load_plugin_textdomain' );
        add_action( 'admin_enqueue_scripts' , __NAMESPACE__ . '\Ba_Rate_My_Posts_Admin::enqueue_styles' );
        add_action( 'admin_menu' , __NAMESPACE__ . '\Ba_Rate_My_Posts_Admin::ba_rate_my_posts_options_page' );
        add_action( 'admin_init' , __NAMESPACE__ . '\Ba_Rate_My_Posts_Admin::ba_rate_my_posts_settings_init' );

        // public
        add_action( 'wp_enqueue_scripts' , __NAMESPACE__ . '\Ba_Rate_My_Posts_Public::enqueue_styles' );
        add_action( 'wp_enqueue_scripts' , __NAMESPACE__ . '\Ba_Rate_My_Posts_Public::enqueue_scripts' );

        //rest
        add_action( 'rest_api_init' , __NAMESPACE__ . '\Ba_Rate_My_Posts_Rest_Controller::register_routes' );


        // shortcodes
        add_shortcode( 'ba-rate-my-posts-rating', __NAMESPACE__ . '\Ba_Rate_My_Posts_Public::register_shortcode_ba_rate_my_posts_rating' );

    }

    /**
     * gets wp_options setting for ba_rate_my_posts_icon and sets default if option not set
     * @since    1.0.0
     * @return string
     */
    public static function get_icon(){
        $icon = get_option( 'ba_rate_my_posts_icon', Ba_Rate_My_Posts::default_icon );
        $icon = esc_attr( $icon );
        return  $icon;
    }

}