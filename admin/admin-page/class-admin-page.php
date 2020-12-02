<?php

class Wps_Breaking_News_Admin_Page{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    private $db_option_name;

    public function __construct($plugin_name){

       $this->plugin_name = $plugin_name;
       $this->db_option_name = WPS_BREAKING_NEWS_OPTIONS_NAME;

        add_action('admin_menu', array( $this, 'add_admin_menu' ), 9);
        add_action('admin_init', array( $this, 'register_and_build_fields' ));
    }

    public function add_admin_menu(){
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page(
            'Breaking News plugin settings',
            'Breaking News',
            'administrator',
            'wps-breaking-news-plugin-settings',
            array( $this, 'display_admin_dashboard' ),
            'dashicons-megaphone',
            26
        );
    }

    /**
     * Dashboard for the admin page
     */
    public function display_admin_dashboard() {

        if(isset($_GET['error_message'])){
            add_action('admin_notices', array($this,'plugin_settings_messages'));
            do_action( 'admin_notices', $_GET['error_message'] );
        }

        require_once plugin_dir_path( dirname( __FILE__ ) ).'partials/'.$this->plugin_name.'-admin-display.php';
    }

    /**
     * Generic error message
     */
    public function plugin_settings_messages(){
        $message = __( 'There was an error. Please try again.', 'wps-breaking-news' );
        $err_code = esc_attr( 'wps_breaking_news_setting' );
        $setting_field = 'wps_breaking_news_setting';

        $type = 'error';
        add_settings_error(
            $setting_field,
            $err_code,
            $message,
            $type
        );
    }

    /**
     * Register option fields
     */
    public function register_and_build_fields() {
        add_settings_section(
            'wps_breaking_news_general_section',
            '',
            '',
            'wps-breaking-news-plugin-settings'
        );


        add_settings_field(
            'intro_text',
            'Into text',
            array( $this, 'setting_field_generator' ),
            'wps-breaking-news-plugin-settings',
            'wps_breaking_news_general_section',
            array (
                'type'      => 'input',
                'subtype'   => 'text',
                'id'        =>  'intro_text',
                'name'      => 'intro_text',
                'required'  => false,
                'value_type'=>'normal',
                'prepend'   =>__('Set a custom intro text ex: BREAKING NEWS','wps-breaking-news'),
                'placeholder'=>'BREAKING NEWS'
            )
        );

        register_setting(
            'wps_breaking_news_general_settings',
            $this->db_option_name
        );
    }

    /**
     * Generate form fields
     */
    public function setting_field_generator($args)
    {
        $options = get_option($this->db_option_name);
	    $wp_data_value = isset($options[$args['name']]) ? $options[$args['name']] : '';

        //General attributes
        $required = '';

        if (isset($args['required'])){
            //check if required is true
            if ($args['required']) {
                $required = 'required';
            }
        }

        // Map field name to match options wps_bn_options[option_name] db row
        $name = $this->db_option_name.'['.$args['name'].']';

        /**
         * Extendable list of types
         */
        switch ($args['type']) {
            case 'input':
                $value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
                if($args['subtype'] != 'checkbox'){
                    $prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
                    $prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
                    $step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
                    $min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
                    $max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
                    $placeholder = (isset($args['placeholder'])) ? 'placeholder="'.$args['placeholder'].'"' : '';


                    if(isset($args['disabled'])){
                        // hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
                        echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'_disabled" '.$step.' '.$max.' '.$min.' name="'.$name.'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.$args['id'].'" '.$step.' '.$max.' '.$min.' name="'.$name.'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
                    } else {
                        echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'" '.$required.' '.$placeholder.' '.$step.' '.$max.' '.$min.' name="'.$name.'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
                    }
                } else {
                    $checked = ($value) ? 'checked' : '';
                    echo '<input type="'.$args['subtype'].'" id="'.$args['id'].'" '.$required.' name="'.$name.'" size="40" value="1" '.$checked.' />';

                }
                break;
        }
    }
}
