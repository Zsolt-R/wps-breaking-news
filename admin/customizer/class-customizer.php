<?php

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register', array( 'Wps_Breaking_News_Customizer', 'register' ) );

class Wps_Breaking_News_Customizer{

    public static function register( $wp_customize ) {

        $db_option_name = WPS_BREAKING_NEWS_CUSTOMIZER_OPTIONS_NAME;

        /******************************
         * PANELS
         * Define a new section to the Theme Customizer
         */
        $wp_customize->add_section(
            'wps_bn_customizer_settings',
            array(
                'title'          => 'Breaking News Settings',
                'description'    => __('Adjust visual appearance','wps-breaking-news'),
                'capability'     => 'edit_theme_options',
            )
        );

        // SETTING
        $wp_customize->add_setting(
            $db_option_name. '[text_color]',
            array(
                'default'    => '#ffffff',
                'type'       => 'option',
                'capability' => 'edit_theme_options',
                'transport'  => 'postMessage',
            )
        );

        // CONTROL
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'wps_bn_display_text_color',
                array(
                    'label'     => __( 'Text Color','wps-breaking-news' ),
                    'settings'  => $db_option_name. '[text_color]', // Add customizer options under one array
                    'section'     => 'wps_bn_customizer_settings',
                )
            )
        );
        // SETTING
        $wp_customize->add_setting(
            $db_option_name. '[background_color]',
            array(
                'default'    => '#dd3333',
                'type'       => 'option',
                'capability' => 'edit_theme_options',
                'transport'  => 'postMessage',
            )
        );

        // CONTROL
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'wps_bn_display_background_color',
                array(
                    'label'     => __( 'Background Color','wps-breaking-news' ),
                    'settings'  => $db_option_name. '[background_color]', // Add customizer options under one array
                    'section'   => 'wps_bn_customizer_settings',
                )
            )
        );

        // SETTING
        $wp_customize->add_setting(
            $db_option_name. '[link_color]',
            array(
                'default'    => '#eeee22',
                'type'       => 'option',
                'capability' => 'edit_theme_options',
                'transport'  => 'postMessage',
            )
        );

        // CONTROL
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'wps_bn_display_link_color',
                array(
                    'label'     => __( 'Link Color','wps-breaking-news' ),
                    'settings'  => $db_option_name. '[link_color]', // Add customizer options under one array
                    'section'   => 'wps_bn_customizer_settings',
                )
            )
        );

        // SETTING
        $wp_customize->add_setting(
            $db_option_name. '[js_target_id]',
            array(
                'default'    => '',
                'type'       => 'option',
                'capability' => 'edit_theme_options',
                'transport'  => 'refresh',
                'sanitize_callback'    => 'custom_css_id_sanitize',
            )
        );

        // CONTROL
        $wp_customize->add_control(
            $db_option_name. '[js_target_id]', // Set a unique ID for the control
            array(
                'type'        => 'text',
                'label'       => __( 'CSS ID', 'wps-breaking-news' ), // Admin-visible name of the control
                'description' => __( 'Hook news ticker into a custom position by add an exiting CSS id. ex: site-header. Default is main body.', 'wps-breaking-news'),
                'section'   => 'wps_bn_customizer_settings',
            )
        );

        // SETTING
        $wp_customize->add_setting(
            $db_option_name. '[js_target_to_end]',
            array(
                'default'    => '',
                'type'       => 'option',
                'capability' => 'edit_theme_options',
                'transport'  => 'refresh',
            )
        );

        $wp_customize->add_control(
            $db_option_name. '[js_target_to_end]', // Set a unique ID for the control
            array(
                'type'        => 'checkbox',
                'label'       => __( 'Add ticker to end of the container',  'wps-breaking-news'  ),
                'description' => __( 'By default the ticker will be hooked at the start of the container specified by the ID',  'wps-breaking-news'  ),
                'section'     => 'wps_bn_customizer_settings',
            )
        );

        // Aid settings edit by adding the "pencil"
        $wp_customize->selective_refresh->add_partial(
            $db_option_name. '[js_target_id]',
            array(
                'selector'        => '.wps-bn-block__inner',
                'render_callback' => '__return_false',
            )
        );

        function custom_css_id_sanitize($str){

            // Remove all html
            $new_str = sanitize_text_field($str);

            //Remove First Dash or dot
            $new_str = preg_replace("/^#|^\./", "", $new_str);

           //Convert whitespaces and underscore to dash
           $new_str = preg_replace("/[\s]/", "-", $new_str);

           return $new_str;
        }
        
    }


    /**
     * This will output the custom WordPress settings to the live theme's WP head.
     * If we have no styles return false so we can conditionally load styles
     * Used by hook: 'wp_head','wp_add_inline_style'
     *
     * @see add_action('wp_head',$func)
     * @since WPS-PRIME 2.0
     */
   public static function customizer_style() {
       $output  = false;
       $styles = '';
       $styles .= self::generate_css_var( '--breaking-news-text-color', 'text_color' );
       $styles .= self::generate_css_var( '--breaking-news-background-color', 'background_color' );
       $styles .= self::generate_css_var( '--breaking-news-link-color', 'link_color' );

       if($styles){
           $output = ':root {'.$styles.'}';
       }

       return $output;
    }
    public static function customizer_output() {
       $output = false;
       $style = self::customizer_style();

       if($style) {
           $output = '<!--BN Customizer CSS-->';
           $output .= '<style type="text/css">';
           $output .= self::customizer_style();
           $output .= '</style>';
           $output .= '<!--/BN Customizer CSS-->';
       }
        echo $output;
    }

    /**
     * Generate Css Variables
     *
     * unit - string (px,em ...)
     */
    public static function generate_css_var( $var_name, $option_id, $unit = '', $echo = false ) {
        $output = '';
        $value  = self::get_customizer_options( $option_id );
        if ( $value ) {
            $output = sprintf(
                '%s:%s%s;',
                $var_name,
                $value,
                $unit
            );
            if ( $echo ) {
                echo $output;
            }
        }
        return $output;
    }

    private static function get_customizer_options( $option_name, $default = false ) {

        $db_options = get_option(WPS_BREAKING_NEWS_CUSTOMIZER_OPTIONS_NAME);

        if ( empty( $db_options ) ) {
            return $default;
        }

        if ( $db_options[ $option_name ] ) {
            return $db_options[ $option_name ];
        } else {
            return $default;
        }

    }
}