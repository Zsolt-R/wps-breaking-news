<?php

class Wps_Breaking_News_Cron{
    public function __construct(){
        //Add cron schedule
        add_filter( 'cron_schedules', array($this,'add_cron_interval') );
        add_action('wps_breaking_news_every_minute_event', array($this,'resolve_expired_breaking_news'));
    }

    /**
     * Setup custom schedule
     * @param $schedules
     * @return mixed
     */
    public function add_cron_interval( $schedules ) {
        $schedules['everyminute'] = array(
            'interval'  => 60, // time in seconds
            'display'   => 'Every Minute'
        );
        return $schedules;
    }

    /**
     * On expiration time delete post meta
     * @return false
     */
    public function resolve_expired_breaking_news(){
        $posts = $this->get_breaking_news();

        if($posts){

            foreach($posts as $post_id){

                $time_meta = get_post_meta($post_id,'_wps-bn-expire-date',true);

                if( $time_meta) {

                    // Get unix timestamp
                    $server_time = strtotime(current_time("Y-m-d H:i"));
                    $post_time = strtotime($time_meta);

                    /**
                     * Meta fields:
                     * _wps-bn-is-breaking-news
                     * _wps-bn-expire-date
                     * Delete meta fields
                     */
                    if($server_time >= $post_time){
                        delete_post_meta($post_id,'_wps-bn-is-breaking-news');
                        delete_post_meta($post_id,'_wps-bn-is-breaking-news');
                    }

                }
            }

        }
        return false;

    }

    /**
     * Generate list of post Id's
     * @return int[]|WP_Post[]
     */
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
new Wps_Breaking_News_Cron();



