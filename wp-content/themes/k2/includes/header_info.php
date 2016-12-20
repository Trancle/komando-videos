<?php
class HeaderInfo {
	private $img;
	private $title;
	private $description;
	private $url;
  private $meta_description;

  public function setTitleImgUrlDesc( $title, $img, $url, $desc ) {
    $this->setTitle($title);
    $this->setImg($img);
    $this->setUrl($url);
    $this->setDescription($desc);
    return $this;
  }

	public function setImg($v) {
    $this->img = $v;
  }
	public function setTitle($v) {
    $this->title = HeaderInfo::truncate($v,100);
  }
	public function setDescription($v) {
    $this->description = HeaderInfo::truncate($v,250);
  }
	public function setMetaDescription($v) {
    $this->meta_description = HeaderInfo::truncate($v,150);
  }
	public function setUrl($v) {
    $this->url = $v;
  }

	public function getImg() {
    return $this->img;
  }
	public function getTitle() {
    return $this->title;
  }
	public function getDescription() {
    return $this->description;
  }
	public function getUrl() {
    return $this->url;
  }
  public function getMetaDescription() {
    if( $this->hasMetaDescription() ) {
      return $this->meta_description;
    }
    return $this->getDescription();
  }
  public function getMetaTitle() {
    if( $this->hasMetaTitle() ) {
      return $this->meta_title;
    }
    return $this->getTitle();
  }

  public function hasMetaDescription() {
    return !is_null($this->meta_description);
  }

  public function hasMetaTitle() {
    return !is_null($this->meta_title);
  }

  public static function truncate($string, $width, $with = "...") {
    if( is_null($string) ) {
      return null;
    }
    if( strlen($string) > $width - strlen($with) ) {
      return substr( $string, 0, $width - strlen($with) ) . $with;
    }
    return $string;
  }

}
