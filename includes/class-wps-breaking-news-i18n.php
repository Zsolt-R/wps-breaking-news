<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wpshapers.com/
 * @since      1.0.0
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/includes
 * @author     Zsolt Revay G. <zsolt.revay.g@gmail.com>
 */
class Wps_Breaking_News_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wps-breaking-news',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
