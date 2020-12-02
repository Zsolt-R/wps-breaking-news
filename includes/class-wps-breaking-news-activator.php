<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wpshapers.com/
 * @since      1.0.0
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/includes
 * @author     Zsolt Revay G. <zsolt.revay.g@gmail.com>
 */
class Wps_Breaking_News_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        if ( ! wp_next_scheduled( 'wps_breaking_news_every_minute_event' ) ) {
            wp_schedule_event( time(), 'everyminute', 'wps_breaking_news_every_minute_event' );
        }
	}

}
