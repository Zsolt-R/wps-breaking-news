<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpshapers.com/
 * @since      1.0.0
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wps_Breaking_News
 * @subpackage Wps_Breaking_News/public
 * @author     Zsolt Revay G. <zsolt.revay.g@gmail.com>
 */
class Wps_Breaking_News_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

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

        wp_register_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/dist/css/wps-breaking-news-plugin.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name);

        // Output customizer CSS to front end
        $custom_style = Wps_Breaking_News_Customizer::customizer_style();

        if ($custom_style) {
            wp_add_inline_style($this->plugin_name, $custom_style);
        }

    }


    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

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

        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/dist/js/wps-breaking-news-plugin.min.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'wps_bn_meta_data', $this->get_js_component_data());
        wp_enqueue_script($this->plugin_name);

    }

    /**
     * Generate data to share with js component
     */
    private function get_js_component_data()
    {
        // Setup defaults
        $meta_data = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'cta_text' => __('BREAKING NEWS','wps-breaking-news'),
            'link_text'=>__('read more','wps-breaking-news'),
            'css_id' => false,
            'post_data' => false,
            'hook_to_end' => false
        );

        // Setup post data
        $post = $this->get_first_breaking_news();

        // Get custom css id
        $options = get_option(WPS_BREAKING_NEWS_OPTIONS_NAME);
        $customizer_options = get_option(WPS_BREAKING_NEWS_CUSTOMIZER_OPTIONS_NAME);

        if($post){
            $meta_data['post_data'] = $post;
        }

        if(isset($customizer_options['js_target_id'])){
            $meta_data['css_id'] = $customizer_options['js_target_id'];
        }

        if(isset($customizer_options['js_target_to_end'])){
            $meta_data['hook_to_end'] = $customizer_options['js_target_to_end'];
        }

        if(isset($options['intro_text'])){
            if(!empty($options['intro_text'])) {
                $meta_data['cta_text'] = $options['intro_text'];
            }
        }

        return $meta_data;
    }

    /**
     * @return array
     *
     */
    private function get_first_breaking_news()
    {
        $output = array();

        $args = array(
            'post_type' => 'post',
            'orderby' => 'date',
            'order'   => 'DESC',
            'numberposts' => 1,  // make sure we have only one post
            'status' => 'publish',
        );

        // Get only posts that have Mark and Date
        $args['meta_query'] = array(
            array(
                'key' => '_wps-bn-is-breaking-news',
                'value' => '',
                'compare' => '!='
            )
        );

        $posts = get_posts($args);

        if($posts){
            $post = $posts[0];

            $custom_title = get_post_meta($post->ID,'_wps-bn-custom-title',true);

            $output['title'] = $custom_title ? $custom_title : $post->post_title;
            $output['permalink'] = get_the_permalink($post->ID);

        }
        return $output;
    }
}