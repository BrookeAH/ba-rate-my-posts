<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              whatbrookesees.com\ba-rate-my-posts
 * @since             1.0.0
 * @package           Ba_Rate_My_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       BA Rate My Posts
 * Plugin URI:        whatbrookesees.com/ba-rate-my-posts
 * Description:       BA Rate My Posts allows all users to view ratings and logged in users to rate any post/page the shortcode is inserted into.
 * Version:           1.0.0
 * Author:            Brooke Adrienne
 * Author URI:        whatbrookesees.com\ba-rate-my-posts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ba-rate-my-posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'BA_RATE_MY_POSTS_VERSION', '1.0.0' );
define( 'BA_RATE_MY_POSTS_DB_VERSION', '1.0.0' );
define( 'BA_RATE_MY_POSTS_PLUGIN_FILE', __FILE__ )  ;


require plugin_dir_path( __FILE__ ) . 'lib/ba-rate-my-posts.php';

\Ba_Rate_My_Posts\Ba_Rate_My_Posts::install();
\Ba_Rate_My_Posts\Ba_Rate_My_Posts::run();

