<?php

namespace K2\Podcast;

/**
 * Class Show
 * 
 * Builds a podcast show object (so we can mess with Chris's head)
 * 
 * @package K2\Podcast
 */
class Show {

    use GetterSetter;

    protected static $transient_cache_var = "k2-podcast-listing-show-";
    protected static $podcast_url = "http://podcast.komando.com/show/";
    protected static $podcast_url_ending = ".xml";
    protected static $type = "podcast";

    private $rss_id;
    private $attributes = array();

    /**
     * These fields will show up in the plugin settings and can be set and stored in the long term storage
     *
     * @return array of extra data fields
     */
    public static function extra_attributes()
    {
        return array(
            "logo_caption" => '',
            "itunes_url" => '',
            "google_play_url" => '',
            "tracked_rss_feed_url" => ''
        );
    }

    /**
     * Returns the id of this Show
     *
     * @return integer which is the id of the podcast/show
     */
    public function get_rss_id() {
        return $this->rss_id;
    }

    /**
     * Sets the id of the podcast/show
     *
     * @param $v integer [in]
     * @return $this
     */
    public function set_rss_id($v) {
        $this->rss_id = $v;
        return $this;
    }

    /**
     * Builds the cache key to access the transient cache for this show
     *
     * @return string containing the transient cache lookup value for this specific podcast/show
     */
    private function cache_key() {
        return self::$transient_cache_var . $this->get_rss_id();
    }

    /**
     * Gets the raw RSS feed content
     *
     * @return string containing the raw xml from this podcast/show's rss feed
     * @throws PodcastRssLookupException
     */
    private function get_rss_content() {
        $cache_content = get_transient( $this->cache_key() );
        if( false === $cache_content ) {
            $cache_content = $this->update_rss_cache();
        }
        return $cache_content;
    }

    /**
     * Updates the cache containing the raw xml from this podcast/show's rss feed
     *
     * @return string
     * @throws PodcastRssLookupException
     */
    public function update_rss_cache() {
        $cache_content = $this->fetch_rss_content();
        set_transient( $this->cache_key(), $cache_content, HOUR_IN_SECONDS );
        return $cache_content;
    }

    /**
     *  Processes the raw xml from this podcast/show's rss feed and stores it in the attributes array
     *
     * @param $rss_content string [in] the raw xml from this podcast/show's rss feed
     * @return $this|bool
     */
    private function translate_rss_to_obj( $rss_content ){
        if(empty($rss_content)){
            return false;
        }
        $show_xml = new \SimpleXMLElement($rss_content);
        $show_xml->registerXPathNamespace("itunes", "http://www.itunes.com/dtds/podcast-1.0.dtd");
        $show_xml->registerXPathNamespace("atom", "http://www.w3.org/2005/Atom");

        $this->set_title( (string) $show_xml->channel->title );
        $this->set_description( (string) $show_xml->channel->description );
        $this->set_logo( (string) $show_xml->xpath('channel/itunes:image')[0]['href']);
        
        $episodes = array();
        foreach($show_xml->channel->item as $item){
            $episodes[] =
                (new \K2\Podcast\Episode())->set_show_id($this->get_rss_id())->load_from_xml($item);
        }
        $this->set_episodes( $episodes );
        return $this;
    }

    /**
     * Gets the RSS content for this Show and sends it off for processing
     *
     * @return $this
     */
    private function load_rss_content(){
        $content = $this->get_rss_content();
        $this->translate_rss_to_obj($content);
        return $this;
    }

    /**
     * This is mainly used to pass all the attribute data to angular
     * FIXME: JUST SEND NEEDED DATA
     * @return array of attributes
     */
    public function get_all_attributes(){
        return array_merge($this->attributes(), array("id" => $this->get_rss_id()));
    }

    /**
     * Gets an array of all of the attributes of this Show
     *
     * @return array of all the attributes related to this podcast/show
     */
    private function attributes()
    {
        if (empty($this->attributes))
        {
            $this->load_rss_content();
            $extra_data = LongTermData::get_object_data(self::$type, $this->get_rss_id());
            foreach( self::extra_attributes() as $key => $default ) {
                $this->attributes[$key] = ( isset($extra_data[$key]) ? $extra_data[$key] : $default );
            }
        }
        return $this->attributes;
    }

    /**
     * Extracts the id for this Show from the feed url
     *
     * @param $url string [in] podcast/show RSS feed url
     * @return int podcast/show id
     */
    public static function get_id_from_url($url){
        return str_replace(".xml", "", str_replace("/show/", "", parse_url($url , PHP_URL_PATH)));
    }

    /**
     * Gets the RSS feed url of this Show
     *
     * @return string RSS feed url built from the podcast/show id
     */
    public function get_rss_url(){
        return self::$podcast_url . $this->get_rss_id() . self::$podcast_url_ending;
    }

    /**
     * Factory method to create new Show
     *
     * @param $id int [in] ID of the podcast
     * @return $this
     */
    public static function new_from_rss( $id ) {
        return (new Show())->load_by_id($id);
    }

    public function load_by_id($id){
        $this->rss_id = intval($id);
        return $this;
    }

    function remove(){
        if(!empty($this->rss_id)){
            delete_transient(self::$transient_cache_var . $this->rss_id);

            if(LongTermData::get_data(self::$type)){
                LongTermData::remove_object(self::$type, $this->rss_id);
            }
            return true;
        }
        return false;
    }

    /**
     * Goes to the podcast web api and grabs the content of the RSS xml file for the show based on the ID
     *
     * @return string Raw content from the http(s) call to the podcast system when looking up the show
     * @throws PodcastRssLookupException When an HTTP lookup fails
     */
    public function fetch_rss_content() {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->get_rss_url());
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($curl);

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if( curl_error($curl) || 200 !== $status_code ) {
            curl_close ($curl);
            throw new PodcastRssLookupException($this->get_rss_url());
        }
        curl_close ($curl);
        return $content;
    }

    public function update_extra_data_field($field, $value){
        return isset(self::extra_attributes()[$field]) && LongTermData::update_object(self::$type, $this->get_rss_id(), $field, $value);
    }

    public function get_featured_player_display_amount(){
        return 3;
    }

    public function get_feed_player_display_amount(){
        return 200;
    }

}