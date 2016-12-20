<?php

namespace K2\Podcast;

class Episode {

    use GetterSetter;

    private $attributes = array();

    /**
     * This function finds an episode by id
     * It is not a very efficient function and can be drastically improved
     * TODO: IMPROVE ME
     *
     * @param $episode_id
     * @return Episode object OR null
     */
    public static function get_episode_by_id($episode_id){
        $shows = Listing::get_all_shows();
        foreach($shows as $show){
            $episodes = $show->get_episodes();
            foreach($episodes as $episode){
                if($episode_id == $episode->get_episode_id()){
                    return $episode;
                }
            }
        }
        return null;
    }

    /**
     * Outputs attributes of an Episode in JSON
     *
     * @param $episode
     * @return array of episode attributes
     */
    public static function get_episode_details_as_json($episode){
        return json_encode($episode->attributes());
    }

    /**
     * Stores the data retrieved from the XML file in the attributes array
     *
     * @param $xml_item SimpleXML [in] xml data to be processed
     * @return $this
     */
    public function load_from_xml($xml_item){
        $this->set_title((string) $xml_item->title);
        $this->set_description((string) $xml_item->description);
        $this->set_image((string) $xml_item->xpath('itunes:image')[0]['href']);
        $this->set_guid((string) $xml_item->guid);
        $this->set_download_url((string) $xml_item->enclosure['url']);
        $this->set_length((int) $xml_item->enclosure['length']);
        $this->set_duration((int) $xml_item->xpath('itunes:duration')[0]);
        $this->set_pub_date((string) $xml_item->pubDate);
        $this->set_audio_type((string) $xml_item->enclosure['type']);
        return $this;
    }

    /**
     * Returns an array of attributes
     *
     * @return array of attributes
     */
    private function attributes()
    {
        return $this->attributes;
    }

    /**
     * Set's the parent id (the show id) which this episode belong's to
     *
     * @param $id integer [in] is the parent show id
     * @return $this
     */
    public function set_show_id($id){
        $this->podcast_id = $id;
        return $this;
    }

    /**
     * Returns the parent show id
     *
     * @return integer
     */
    public function get_show_id(){
        return $this->podcast_id;
    }

    /**
     * Returns the id of this Episode
     *
     * @return int
     */
    public function get_episode_id(){
        preg_match('/([0-9]+)$/', $this->get_guid(), $matches);
        return isset($matches[1]) ? (int) $matches[1] : null;
    }

    /**
     * Returns the Show object which created this Episode
     * @return $this
     */
    public function get_show(){
        return Listing::get_show_by_id($this->get_show_id());
    }

}
