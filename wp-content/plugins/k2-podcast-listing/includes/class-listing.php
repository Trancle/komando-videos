<?php

namespace K2\Podcast;

class Listing {
    
    protected static $latest_featured_episode = null;
    protected static $transient_cache_latest_episode_var = "k2-podcast-latest_episode";
    
    public static function get_latest_episode(){
        self::$latest_featured_episode = "";//get_transient(self::$transient_cache_latest_episode_var);
        $latest_date = 0;
        $latest_episode = false;
        if(empty(self::$latest_featured_episode)){
            $podcasts = self::get_podcast_list();
            foreach($podcasts as $podcast){
                $episodes = $podcast->get_episodes();
                if(is_null($episodes[0]) || !isset($episodes[0]->get_episode_info()["pub_date"])){
                    continue;
                }
                $date = strtotime($episodes[0]->get_episode_info()["pub_date"]);
                if($date > $latest_date){
                    $latest_date = $date;
                    $latest_episode = $episodes[0];
                }
            }
            self::$latest_featured_episode = $latest_episode;
            set_transient(self::$transient_cache_latest_episode_var, self::$latest_featured_episode, HOUR_IN_SECONDS);
        }
        return self::$latest_featured_episode;
    }

    public static function update_position($id, $direction){
        LongTermData::move_object_position('podcast', $id, $direction);
    }

    public static function update_sort_order($new_sorting_order){
        foreach($new_sorting_order as $index => $id){
            $original_sort_order = self::get_podcast_show_ids();
            $current_index = array_search($id, $original_sort_order);
            LongTermData::swap_position('podcast', $current_index, $index);
        }
        return true;
    }

    public static function update_all_podcast_long_term_data($data){
        $data_from_angular = angular_to_php_json_decode($data, true);

        $order_by = array();

        foreach($data_from_angular as $data){
            if(!isset($data['data']) || !isset($data['id'])){
                return false;
            }

            $id = $data['id'];
            $order_by[] = $id;

            $show = Show::new_from_rss($id);
            foreach($data['data'] as $field => $value){
                $show->update_extra_data_field($field, $value);
            }
        }

        self::update_sort_order($order_by);
        return true;
//        LongTermData::update_all_objects('podcast', ); //TODO: DON'T GO STRAIGHT TO DB, PULL USING SHOW FUNCTIONS
    }

    public static function get_long_term_show_data_json(){
        return php_to_angular_json_encode(LongTermData::get_data('podcast')); //TODO: DON'T GO STRAIGHT TO DB, PULL USING SHOW FUNCTIONS
    }

    public static function get_podcast_show_ids(){
        $show_ids = array();
        $data = LongTermData::get_data('podcast');
        foreach($data as $key => $podcast_data){
            $show_ids[] = $podcast_data['id'];
        }
        return $show_ids;
    }
    
    public static function get_show_by_id($id){
        if(in_array($id, self::get_podcast_show_ids())){
            return Show::new_from_rss($id);
        }
        return null;
    }

    public static function get_podcast_list(){
        return self::get_all_shows();
    }

    //returns all show objects
    public static function get_all_shows(){
        $shows = array();
        foreach(self::get_podcast_show_ids() as $id){
            $shows[] = Show::new_from_rss($id);
        }

        return $shows;
    }

    /**
     * @return array of \K2\Podcast\Show
     */
    public static function get_all_show_data_only(){
        return array_map( function($show) {
            return $show->get_all_attributes();
        }, self::get_all_shows() );
    }

    /**
     * Done in memory
     * @param $url
     * @return bool
     */
    public static function add_show($url){
        $id = Show::get_id_from_url($url);
        if( !in_array($id, self::get_podcast_show_ids()) ) {
            try {
                LongTermData::add_object('podcast', $id, Show::extra_attributes());
                return Show::new_from_rss($id);
            } catch(PodcastRssLookupException $e) {
                // Recover from failure to look up the data from podcast system
                echo "Fatal Error: Failed to retrieve data from RSS Feed at " . $e->get_url() . ".";
                return false;
            }
        }
        return false;
    }

/*
    //todo: persist the in-memory variable
public function commit() {
    //set_option('magicname from other variables', wp_marshall($in_memory_listing));
}
*/
    
    public static function remove_show($id){
        if((array_search($id, self::get_podcast_show_ids())) !== false) {
            $show = Show::new_from_rss($id);
            $ret = $show->remove();
            unset($show);
            return $ret;
        }
        return false;
    }

}
