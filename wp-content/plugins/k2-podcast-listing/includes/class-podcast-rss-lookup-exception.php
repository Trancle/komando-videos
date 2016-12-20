<?php

namespace K2\Podcast;

/**
 * Class PodcastRssLookupException
 * @package K2\Podcast
 */
class PodcastRssLookupException extends \Exception {
    private $url;
    public function __construct( $url )
    {
        parent::__construct();
        $this->url = $url;
    }

    public function get_url(){
        return $this->url;
    }
}