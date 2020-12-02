<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpshapers.com/
 * @since      1.0.0
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/admin
 * @author     Zsolt Revay G. <zsolt.revay.g@gmail.com>
 */
class Wps_Breaking_News_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->load_customizer();
        $this->load_meta_fields();
        $this->load_cron_jobs();
        $this->load_admin_page();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wps_Breaking_News_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wps_Breaking_News_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/dist/css/wps-breaking-news-plugin-admin.min.css', array(), $this->version, 'all' );
        wp_register_style($this->plugin_name.'-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css', array(), '1.8','all');
        wp_register_style($this->plugin_name.'-jquery-ui-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css', array(), '1.6.3','all');

        // Condition script to 'post'
        global $hook_suffix;
        if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix &&  'post' == get_post_type()) {
            wp_enqueue_style($this->plugin_name);
            wp_enqueue_style( $this->plugin_name.'-jquery-ui' );
            wp_enqueue_style( $this->plugin_name.'-jquery-ui-timepicker' );
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wps_Breaking_News_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wps_Breaking_News_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/dist/js/wps-breaking-news-plugin-admin.min.js', array( 'jquery','jquery-ui-datepicker' ), $this->version, false );
        wp_register_script( $this->plugin_name.'-jquery-ui-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js', array( 'jquery','jquery-ui-datepicker' ), '1.6.3', false );

        // Condition script loading to 'post' only
        global $hook_suffix;
        if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix &&  'post' == get_post_type()) {
            wp_enqueue_script($this->plugin_name);
            wp_enqueue_script($this->plugin_name.'-jquery-ui-timepicker');
        }
	}

    /**
     * This outputs the javascript needed to automate the live settings preview.
     * Also keep in mind that this function isn't necessary unless your settings
     * are using 'transport'=>'postMessage' instead of the default 'transport'
     * => 'refresh'
     *
     * Used by hook: 'customize_preview_init'
     *
     * @see add_action('customize_preview_init',$func)
     **
     */
    public function enqueue_customizer_assets() {
        wp_enqueue_script( $this->plugin_name.'-customizer', plugin_dir_url( __FILE__ ) . 'assets/dist/js/wps-breaking-news-customizer.min.js', array( 'jquery', 'customize-preview' ), $this->version, true);
    }

    private function load_customizer(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/customizer/class-customizer.php';
    }

    private function load_meta_fields(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/meta-fields/class-meta-fields.php';
    }

    private function load_cron_jobs(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/cron/class-plugin-cron.php';
    }



    private function load_admin_page(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin-page/class-admin-page.php';
        new Wps_Breaking_News_Admin_Page($this->plugin_name);
    }

}
