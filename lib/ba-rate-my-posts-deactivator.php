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
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */

namespace Ba_Rate_My_Posts;

class Ba_Rate_My_Posts_Deactivator {

	/**
	 * All functions necessary to deactivate.
     * Since nothing from activation needs to be removed until uninstall, only rewrites rules.
	 * @since    1.0.0
	 */
	public static function deactivate() {
        flush_rewrite_rules();
	}

}
