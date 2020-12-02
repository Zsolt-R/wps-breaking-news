<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wpshapers.com/
 * @since      1.0.0
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/includes
 * @author     Zsolt Revay G. <zsolt.revay.g@gmail.com>
 */
class Wps_Breaking_News_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        wp_clear_scheduled_hook( 'wps_breaking_news_every_minute_event' );
	}

}
