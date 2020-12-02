<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpshapers.com/
 * @since             1.0.0
 * @package           Wps_Breaking_News
 *
 * @wordpress-plugin
 * Plugin Name:       Breaking News
 * Plugin URI:        https://wpshapers.com/
 * Description:       Manage and display a Breaking News ticker on your website. Adjust visual settings directly in the customizer, for more options visit Breaking News settings.
 * Version:           1.0.0
 * Author:            Zsolt Revay G.
 * Author URI:        https://wpshapers.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wps-breaking-news
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPS_BREAKING_NEWS_VERSION', '1.0.0' );

/**
 * Plugin options name in database
 */
define('WPS_BREAKING_NEWS_OPTIONS_NAME','wps_bn_options');
define('WPS_BREAKING_NEWS_CUSTOMIZER_OPTIONS_NAME','wps_bn_customizer_options');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wps-breaking-news-activator.php
 */
function activate_wps_breaking_news() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wps-breaking-news-activator.php';
	Wps_Breaking_News_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wps-breaking-news-deactivator.php
 */
function deactivate_wps_breaking_news() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wps-breaking-news-deactivator.php';
	Wps_Breaking_News_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wps_breaking_news' );
register_deactivation_hook( __FILE__, 'deactivate_wps_breaking_news' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wps-breaking-news.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wps_breaking_news() {

	$plugin = new Wps_Breaking_News();
	$plugin->run();

}
run_wps_breaking_news();
