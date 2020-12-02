<?php

class Wps_Breaking_News_Admin_Page_Custom_Data
{

    public function render_post_list()
    {
        $post_id_list = $this->get_breaking_news();
        $output = '';

        if ($post_id_list) {

            foreach ($post_id_list as $post_id) {

                $custom_title = get_post_meta($post_id, '_wps-bn-custom-title', true);

                $output .= '<li>';
                $output .= '<a href="'.get_edit_post_link($post_id).'" title="Open in a new window" target="_blank">edit</a> | ';
                $output .= '<a href="'.get_the_permalink($post_id).'" title="Open in a new window" target="_blank">view</a>';
                $output .= '&nbsp;&nbsp;&nbsp;'. get_the_title($post_id);
                $output .= $custom_title ? ' ( custom: '.$custom_title .' )':'';
                $output .= '</li>';
            }

            $output = '<ul>'.$output.'</ul>';

        } else {
            $output = 'No breaking news';
        }

        return $output;
    }

    private function get_breaking_news()
    {

        $args = array(
            'post_type' => 'post',
            'orderby' => 'date',
            'order' => 'DESC',
            'numberposts' => -1,  // get all posts
            'status' => 'publish',
            'fields' => 'ids' // get only Id's
        );

        // Get only posts that have Mark and Date
        $args['meta_query'] = array(
            array(
                'key' => '_wps-bn-is-breaking-news',
                'value' => '',
                'compare' => '!='
            )
        );

        return get_posts($args);
    }
}