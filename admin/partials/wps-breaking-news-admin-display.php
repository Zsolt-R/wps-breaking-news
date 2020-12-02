<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpshapers.com/
 * @since      1.0.0
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/admin/partials
 */

require_once plugin_dir_path( dirname(__DIR__ )). 'admin/admin-page/class-admin-list-posts.php';
$custom_data = new Wps_Breaking_News_Admin_Page_Custom_Data();
$curr_timezone = get_option('timezone_string');
?>
<div class="wrap">
    <h2>Breaking News plugin settings</h2>
    <?php settings_errors(); ?>
    <form method="POST" action="options.php">
        <?php
        settings_fields( 'wps_breaking_news_general_settings' );
        do_settings_sections( 'wps-breaking-news-plugin-settings' );
        ?>
        <?php submit_button(); ?>
    </form>
    <hr/>
    <h3>Timezone info</h3>
    <?php if($curr_timezone): ?>
        <p>Your current timezone is: <?php echo get_option('timezone_string'); ?></p>
    <?php endif; ?>
    <p>Make sure your timezone is correct, <a href="<?php echo admin_url( 'options-general.php' )?>">go to settings</a></p>
    <hr/>
    <h3>Visual Settings</h3>
    <p>Visual settings in customizer under "Breaking News Settings" panel, <a href="<?php echo admin_url( 'customize.php' )?>">edit now</a></p>
    <hr/>
    <h3>Breaking News</h3>
    <?php echo $custom_data->render_post_list(); ?>
</div>