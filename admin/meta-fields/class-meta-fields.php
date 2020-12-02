<?php

class Wps_Breaking_News_Meta_Fields{
    public function __construct(){
       add_action('init',array($this,'register_meta_fields'));
       add_action('add_meta_boxes',array($this,'register_meta_box'));
       add_action( 'save_post', array($this,'save_meta_data'));
    }

    public function register_meta_fields(){
        register_post_meta( 'post', '_wps-bn-custom-title', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can( 'edit_posts' );
            }
        ) );
        register_post_meta( 'post', '_wps-bn-is-breaking-news', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'boolean',
            'auth_callback' => function() {
                return current_user_can( 'edit_posts' );
            }
        ) );
        register_post_meta( 'post', '_wps-bn-expire-date', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can( 'edit_posts' );
            }
        ) );
    }

    public function register_meta_box(){
        add_meta_box(
            'wps-bn-settings-meta-box',
            __( 'Breaking News', 'wps-breaking-news' ),
            array($this,'meta_box_settings'),
            'post',
        'side',

        );
    }
    public function  meta_box_settings( $post ) {

        wp_nonce_field( 'wps-bn-setup-bn_'.$post->ID, 'wps_bn_setup_nonce' );


        /**
         * _wps-bn-custom-title
         * _wps-bn-is-breaking-news
         */

        $custom_title = get_post_meta( $post->ID, '_wps-bn-custom-title', true );
        $is_breaking = get_post_meta( $post->ID, '_wps-bn-is-breaking-news', true );
        $expire_date = get_post_meta( $post->ID, '_wps-bn-expire-date', true );

        ?>
        <p>
        <label for="wps-bn-custom-title"><strong><?php _e( 'Custom Title', 'wps-breaking-news' ); ?></strong></label><br/>
            <em><?php _e('Set a custom title for the news ticker, this will be used instead of the post title.', 'wps-breaking-news'); ?></em><br/>
        <input id="wps-bn-custom-title" name="_wps-bn-custom-title" type="text" class="widefat" placeholder="ex: Amazing breaking news title" value="<?php echo  $custom_title; ?>">
        </p>
        <p id="wps-bn-expire-enable">
            <input type="checkbox" class="widefat" id="wps-bn-is-breaking-news" value="true" name="_wps-bn-is-breaking-news" <?php isset(  $is_breaking ) ? checked(  $is_breaking, 'true', true ) : ''; ?> />
            <label for="wps-bn-is-breaking-news"><strong><?php _e( 'Mark as breaking news', 'wps-breaking-news' ); ?></strong></label>
        </p>
        <p id="wps-bn-expire-utility">
            <label for="wps-bn-expire-date"><strong><?php _e( 'Expire date', 'wps-breaking-news' ); ?></strong></label><br/>
            <em><?php _e('Remove breaking news mark on date', 'wps-breaking-news'); ?></em><br/>
            <input id="wps-bn-expire-date" name="_wps-bn-expire-date" type="text" class="widefat"  value="<?php echo  $expire_date; ?>" readonly>
        </p>
        <?php
    }

    public function save_meta_data($post_id){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Verify that a nonce is correct and unexpired with the respect to a specified action. nonce / nonce_action
        if ( ( isset( $_POST['wps-bn-setup-bn_'.$post_id] ) ) && ( ! wp_verify_nonce( $_POST['wps-bn-setup-bn_'.$post_id], 'wps_bn_setup_nonce' ) ) ) {
            return;
        }

        // Check if we are on a page and not other post type and user is allowed to edit these fields.
        // Check current user capabilities
        if ( ( isset( $_POST['post_type'] ) ) && ( 'office' == $_POST['post_type'] ) ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }


        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['_wps-bn-custom-title'] ) ) {
            $data = sanitize_text_field( $_POST['_wps-bn-custom-title'] );
            update_post_meta( $post_id, '_wps-bn-custom-title', $data );
        }

        /**
         * If breaking news is unchecked remove expire time
         */
        if ( isset( $_POST['_wps-bn-is-breaking-news'] ) ) {
            update_post_meta( $post_id, '_wps-bn-is-breaking-news', 'true' );
        }else{
            update_post_meta( $post_id, '_wps-bn-is-breaking-news', '' );
            delete_post_meta( $post_id, '_wps-bn-expire-date');
        }

        if ( isset( $_POST['_wps-bn-expire-date'] ) && isset( $_POST['_wps-bn-is-breaking-news'] ) ) {
            $date =  date("Y-m-d H:i",strtotime($_POST['_wps-bn-expire-date'] ));

            /**
             * Make sure we don't have an empty / null value
             * JQuery datepicker
             */
            if(!empty($date)) {
                update_post_meta($post_id, '_wps-bn-expire-date', $date);
            }
        }
    }

}
new Wps_Breaking_News_Meta_Fields();