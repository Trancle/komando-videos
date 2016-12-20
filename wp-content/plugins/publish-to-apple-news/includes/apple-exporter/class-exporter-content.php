<?php
namespace Apple_Exporter;

/**
 * Represents a generic way to represent content that must be exported. This
 * can be filled based on a WordPress post for example.
 *
 * @since 0.2.0
 */
class Exporter_Content {

	/**
	 * ID of the content being exported.
	 *
	 * @var int
	 * @access private
	 */
	private $id;

	/**
	 * Title of the content being exported.
	 *
	 * @var string
	 * @access private
	 */
	private $title;

	/**
	 * The content being exported.
	 *
	 * @var string
	 * @access private
	 */
	private $content;

	/**
	 * Intro for the content being exported.
	 *
	 * @var string
	 * @access private
	 */
	private $intro;

	/**
	 * Cover image for the content being exported.
	 *
	 * @var string
	 * @access private
	 */
	private $cover;

	/**
	 * Byline for the content being exported.
	 *
	 * @var string
	 * @access private
	 */
	private $byline;

	/**
	 * Settings for the content being exported.
	 *
	 * @var Settings
	 * @access private
	 */
	private $settings;

	/**
	 * Contstructor.
	 *
	 * @param int $id
	 * @param string $title
	 * @param string $content
	 * @param string $intro
	 * @param string $cover
	 * @param string $byline
	 * @param Settings $settings
	 */
	function __construct( $id, $title, $content, $intro = null, $cover = null, $byline = null, $settings = null ) {
		$this->id       = $id;
		$this->title    = $title;
		$this->content  = $content;
		$this->intro    = $intro;
		$this->cover    = $cover;
		$this->byline   = $byline;
		$this->settings = $settings ?: new Exporter_Content_Settings();
	}

	/**
	 * Get the content ID.
	 *
	 * @return int
	 * @access public
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * Get the content title.
	 *
	 * @return strings
	 * @access public
	 */
	public function title() {
		return $this->title;
	}

    /*
     * Hook for content() function, modifies content before proceeding
     */
    public function modifyContentHook(){
        //$this->showOnlyFirstPage();
        $this->stripOutNextPage();
        $this->replacePodcastPlayer();
        $this->fixNumberedHeaders();
        $this->repairLocalLinks();
        $this->telephoneizePhoneNumbers();
    }

    /**
     * We only want to see the first page in the apple news app
     */
    public function showOnlyFirstPage(){
        if(false !== strpos($this->content, '<!--nextpage-->')){
            $this->content = explode('<!--nextpage-->', $this->content);
//            $this->content = $this->content[0];
            $this->content = $this->content[0] . '<np></p>';
            $this->content = preg_replace('/\<p\>(.*)\<np\>\<\/p\>/', '', $this->content);
            $this->content = str_replace("\n", "<br>", $this->content);
            $this->content .= "<p><a href=\"" . get_post_permalink($this->id) . "/2\">Continue reading...</a></p>";
        }
    }

  public function stripOutNextPage(){
    $this->content = preg_replace('/\<p\>.*?\<\!\-\-nextpage\-\-\>\<\/p\>/', '', $this->content);
    $this->content = preg_replace('/\[nextpage\].*?\[\/nextpage\]/', '', $this->content);
    $this->content = preg_replace('/\<\!\-\-nextpage\-\-\>/', '', $this->content);
  }

  public function fixNumberedHeaders(){
    $this->content = preg_replace('/\<h([0-6])\>([0-9]{1,2})\./', '<h$1>$2:', $this->content);
  }

  public function repairLocalLinks(){
    $this->content = preg_replace('/href\=\"\/([0-9]{1,})\"/', 'href="https://www.komando.com/$1"', $this->content);
  }

  public function stripScripts(){
    $this->content = preg_replace('/\<script\>.*?\<\/script\>/s', '', $this->content);
  }

  public function stripStyles(){
    $this->content = preg_replace('/\<style\>.*?\<\/style\>/s', '', $this->content);
  }

    public function replacePodcastPlayer(){
      $this->content = preg_replace('/\<div class\=\"featured\-player\-wrapper clearfix\"\>.*?\<h3 class\=\"featured\-player\-text\"\>(.*?)\<\/h3\>.*?podcastid\-([0-9]{1,}).*?\<\/ul\>.*?\<\/div\>/s', '<p><a href="http://www.komando.com/listen/episode/$2">Play Podcast: $1</a></p>', $this->content);
//      $this->content = preg_replace('/\[podcast\_player id\=\"([0-9]{1,})\" title\=\"(.*?)\"\]/', '<a href="http://www.komando.com/listen/episode/$1">Play: $2</a>', $this->content);
    }

    public function telephoneizePhoneNumbers(){
      $this->content = preg_replace('/\(([0-9]{3})\)\s*([0-9]{3})\-([0-9]{4})/', '<a href="tel:+1-$1-$2-$3">($1) $2-$3</a>', $this->content);
      $this->content = preg_replace('/([0-9]{3})\-([0-9]{3})\-([0-9]{4})/', '<a href="tel:+1-$1-$2-$3">($1) $2-$3</a>', $this->content);
    }

    /**
	 * Get the content.
	 *
	 * @return string
	 * @access public
	 */
	public function content() {
		$this->modifyContentHook();
        return $this->content;
	}

	/**
	 * Get the content intro.
	 *
	 * @return string
	 * @access public
	 */
	public function intro() {
		return $this->intro;
	}

	/**
	 * Get the content cover.
	 *
	 * @return string
	 * @access public
	 */
	public function cover() {
		return $this->cover;
	}

	/**
	 * Get the content byline.
	 *
	 * @return string
	 * @access public
	 */
	public function byline() {
		return $this->byline;
	}

	/**
	 * Get the content settings.
	 *
	 * @return Settings
	 * @access public
	 */
	public function get_setting( $name ) {
		return $this->settings->get( $name );
	}

	/**
	 * Update a property, useful during content parsing.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @access public
	 */
	public function set_property( $name, $value ) {
		if ( property_exists( $this, $name ) ) {
			$this->$name = $value;
		}
	}

	/**
	 * Get the DOM nodes.
	 *
	 * @return array of DomNodes
	 * @access public
	 */
	public function nodes() {
		// Because PHP's DomDocument doesn't like HTML5 tags, ignore errors.
		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $this->content() );
		libxml_clear_errors( true );

		// Find the first-level nodes of the body tag.
		return $dom->getElementsByTagName( 'body' )->item( 0 )->childNodes;
	}

}
