<?php
/**
 * @package k2ia_ia
 */
namespace K2 {
	class InstantArticlesFilters {
		/**
		 * The class constructor.
		 */
		public function __construct(){
			$this->filter_dispatcher();
		}

		/**
		 * Dispatches the filters needed to format the content for Instant Articles.
		 * @return void
		 */
		private function filter_dispatcher(){
			$filters = array(
				"images",
				"social",
				"headlines",
				"empty_tags",
				"audio",
				"podcast",
				"videos",
				"empty_tags_paragraph",
				"ad_unit",
				"splash_video",
				"image_gallery",
			);
			foreach( $filters as $filter ) {
				add_filter( 'k2ia_content', 	array($this, $filter) );
			}
		}

		/**
		 * Add splash video to the top of the article
		 * @param mixed $content
		 * @return string $content
		 */
		public function splash_video($content){

			$splash_video_html = "";

			$video = get_post_meta( get_the_ID(), 'article_videos_meta_url' )[0];
			if(!empty($video)){
				if(class_exists("Kom_Article_Videos")){
					$video = apply_filters('get_youtube_embed_url', $video);
					$video = apply_filters('get_vimeo_embed_url', $video);
				}
				$splash_video_html = '
				<figure class="op-social">
                	<iframe width="500" height="375" src="' . $video . '" frameborder="0" allowfullscreen></iframe>
				</figure>
				';
			}

			return $splash_video_html . $content;
		}

		/**
		 * Add image_gallery to the article
		 * @param mixed $content
		 * @return string $content
		 */
		public function image_gallery($content){

			$image_gallery_html = '';

			$gallery_images = json_decode(get_post_meta( get_the_ID(), 'gallery_images' )[0],true);
			if(!is_array($gallery_images)) return $content;

			$image_data = array();
			foreach($gallery_images as $image){
				if(empty($image['image_url'])) continue;
				$image_data[] = array(
					'url' => strip_tags($image['image_url']),
					'source' => strip_tags($image['photo_credit']),
					'source_link' => strip_tags($image['credit_link']),
					'title' => strip_tags($image['title']),
					'caption' => (strlen($image['caption_text']) > 180 ? substr(strip_tags($image['caption_text']), 0, 180) . '&hellip;' : strip_tags($image['caption_text'])),
			);
			}

			if(sizeof($image_data) > 0){
				$image_gallery_html = '
<figure class="op-slideshow">';
				foreach($image_data as $image){

					if(!empty($image['source_link'])){
						$image['source'] = '<a href="' . $image['source_link'] . '">' . $image['source'] . '</a>';
					}

					if(!empty($image['source'])){
						$image['source'] = '<cite class="op-vertical-below op-right">' . $image['source'] . '</cite>';
					}


					$image_gallery_html .= '
	<figure data-feedback="fb:likes,fb:comments">
		<img src="' . $image['url'] . '" />';
						$image_gallery_html .= '
		<figcaption class="op-vertical-below">
      		<h1 class="op-vertical-above op-center">' . $image['title'] . '</h1>
      		' . $image['caption'] . '
      		' . $image['source'] . '
    	</figcaption>';
					$image_gallery_html .= '
	</figure>';
				}
				$image_gallery_html .= '
</figure>';
			}

			return $image_gallery_html. $content;
		}

		/**
		 * Format the images for Instant Articles.
		 * @param mixed $content
		 * @return void
		 */
		public function images($content){
			$data_feedback = $this->buildFigureElementAttributes();
			$regex_extract_image = '((?:<a.*?rel="[\w-\s]*?attachment[\w-\s]*?".*?>)?<img.*?class="[\w-\s]*?wp-image[\w-\s]*?".*?>(?:<\/a>)?)';

			// The image is directly at the beginning of the <p> Tag.
			/**/
			$content = preg_replace(
				'/<p>\s*?' . $regex_extract_image . '(.*?)<\/p>/',
				'<figure>$1</figure><p>$2</p>',
				$content
			);

			// The image is directly at the end of the <p> Tag.
			/**/
			$content = preg_replace(
				'/<p>(.*?)' . $regex_extract_image . '\s*?<\/p>/',
				'<p>$1</p><figure>$2</figure>',
				$content
			);

			// We need to delete the elements inside <figure> Tag for a correct instant article structure.
			/**/
			return preg_replace(
				'/<figure(.*?)>/',
				'<figure'.$data_feedback.'>',
				$content
			);
		}

		/**
		 * Format the youtube videos for Instant Articles.
		 * @param mixed $content
		 * @return void
		 */
		public function videos($content){
			$data_feedback = $this->buildFigureElementAttributes();
			$content  = closeTags($content);
			$dom = strGetHtml($content);
			$sources = $this->media_src_walker( 'video', $dom, function( $elem, $src ) {
				return '<figure '.$data_feedback.'data-mode=aspect-fit><video loop controls>'.$src.'</video></figure>';
			} );
			return $dom;
		}

		/**
		 * Format the audio for Instant Articles.
		 * @param mixed $content
		 * @return void
		 */
		public function audio($content){
			$data_feedback = $this->buildFigureElementAttributes();
			$content  = closeTags($content);
			$dom = strGetHtml($content);
			$sources = $this->media_src_walker( 'audio', $dom, function( $elem, $src ) {
				return '<figure '.$data_feedback.'><img src="http://static.komando.com/websites/common/v2/img/fb-instant-article-default-podcast.jpg"><audio controls autoplay>'.$src.'</audio></figure>';
			} );
			return $dom;
		}

		/* Media Source Walker
		*
		* Walks the HTML looking for the $tag_name, in the $dom. When it finds it, calls the $call_back with arguments for the element found and the src tag contents
		*/
		private function media_src_walker( $tag_name, $dom, $call_back ) {
			// get DOM from string content
			foreach($dom->find($tag_name) as $elem){
				$type = '';
				$src = '';
				if(isset($elem->src)){
					if(!empty($elem->type)){
						$type = 'type="'.$elem->type.'"';
					}
					if(!empty($elem->src)){
						$src = '<source src="'.$elem->src.'" '.$type.'   />';
					}
				}else {
					if (!is_null($elem->find('source'))) {
						foreach ($elem->find('source') as $source) {
							if (!empty($source->type)) {
								$type = 'type="' . $source->type . '"';
							}
							if (!empty($source->src)) {
								$src .= '<source src="'.$source->src.'" '.$type.'   />';
							}
						}
					}
				}
				$elem->outertext = call_user_func($call_back, $elem, $src);
			}
		}

		/**
		 * Format h3, h4 and h5 to h2's for Instant Articles.
		 * @param mixed $content
		 * @return void
		 */
		public function headlines($content){
			// Replace h3, h4, h5, h6 with h2
			return preg_replace(
				'/<h[3,4,5,6][^>]*>(.*)<\/h[3,4,5,6]>/sU',
				'<h2>$1</h2>',
				$content
			);
		}

		/**
		 * empty_tags function.
		 * @param mixed $content
		 * @return void
		 */
		public function empty_tags($content){
			// Replace empty characters
			return preg_replace(
				'/<p>(\s|(\&nbsp;))*<\/p>/',
				'',
				$content
			);
		}
		/**
		 * empty_tags_paragraph function.
		 * @param mixed $content
		 * @return void
		 */
		public function empty_tags_paragraph($content){
			$regexstr = '/<p>(\s*|\&nbsp;)<figure(.*?)<\/p>/s';
			$content  = preg_replace($regexstr,'<figure $2', $content);

			$regexstr = '/<p><span class="[\w-\s]*?"><figure(.*?)<\/span><\/p>/s';
			$content  = preg_replace($regexstr,'<figure $1', $content);

			$regexstr = '/<p class="[\w-\s]*?"><span class="[\w-\s]*?"><figure(.*?)<\/span><\/p>/s';
			$content  = preg_replace($regexstr,'<figure $1', $content);

			$regexstr = '/<p><a class="[\w-\s]*?"><figure(.*?)<\/a><\/p>/s';
			$content  = preg_replace($regexstr,'<figure $1', $content);

			$regexstr = '/<p><a><figure(.*?)<\/a><\/p>/s';
			$content  = preg_replace($regexstr,'<figure $1', $content);

			$regexstr = '/<p class="[\w-\s]*?"><a class="[\w-\s]*?"><figure(.*?)<\/a><\/p>/s';
			$content  = preg_replace($regexstr,'<figure $1', $content);

			$regexstr = '/<a>(\s*|\&nbsp;)<figure(.*?)<\/a>/s';
			$content  = preg_replace($regexstr,'<figure $2', $content);

			$regexstr = '/<a class="[\w-\s]*?"><figure(.*?)<\/a>/s';
			$content  = preg_replace($regexstr,'<figure $1', $content);

			$regexstr = '/<p class="[\w-\s]*?"><figure(.*?)<\/p>/s';
			return preg_replace($regexstr,'<figure $1', $content);
		}

		/**
		 * add_ads function.
		 * @param mixed $content
		 * @return void,
		 */
		public function ad_unit($content){
			$count      = 0;
			$results    = [];
			$ad_url    = InstantArticlesOptions::getAdUnitConfigurationValues();
			$ad_url    = str_replace('\'', '"',$ad_url);
			$ad_url    = str_replace(' ', '',$ad_url);
			$ads_config = json_decode($ad_url);
			$total_number_of_ads = count($ads_config);

			$regex_extract_paragraph = '/<p(.*?)>(.*?)<\/p>/';
			preg_match_all($regex_extract_paragraph, $content, $results);
			$number_of_paragraphs = count($results[0]);

			for($index=0; $index < $number_of_paragraphs && $count < $total_number_of_ads; $index++){
				if( $index%InstantArticlesOptions::getTotalParagraphPerAd() == 0 ){
					$remplace = '<figure class="op-ad"><iframe src="http://www.komando.com/ads?unit='.$ads_config[$count]->name.'&width='.$ads_config[$count]->width.'&height='.$ads_config[$count]->height.'" height="'.($ads_config[$count]->height+10).'" width="'.$ads_config[$count]->width.'"></iframe></figure>';
					$content = str_replace($results[0][$index],$results[0][$index].$remplace, $content);
					$count++;
				}
			}
			return $content;
		}

		/**
		 * social function.
		 * @param mixed $content
		 * @return void,
		 */
		public function social($content){
			$regex_extract_embed = '/<div (.*?)class="((.*?)embed(.*?))">(.*?)<\/div>/';
			return preg_replace($regex_extract_embed,'<figure class="op-social" data-mode=aspect-fit>$5</figure>',$content);
		}

		/**
		 * podcast  function.
		 * @param mixed $content
		 * @return void,
		 */
		public function podcast($content){
			$regex_extract_podcast = '/\[podcast_player.*?id="((.*?))".*?\]/';
		    return preg_replace_callback(
				$regex_extract_podcast,
				function($matches) {
					$episode = $this->getPodcastWithEpisodeId( $matches[1] );
					if( isset($episode->id) && $episode->id == $matches[1] ){
						$data_feedback       = $this->buildFigureElementAttributes();
						$podcast_img         = (isset($episode->image_public_url)) ? $episode->image_public_url : 'http://static.komando.com/websites/common/v2/img/fb-instant-article-default-podcast.jpg';
						$podcast_title       = (isset($episode->title)) ? $episode->title : '';
						$podcast_description = (isset($episode->summary)) ? $episode->summary : '';
						$download_link  = 'http://podcast.komando.com/episode/'.$matches[1].'/download.mp3';
						$podcast = '<figure '.$data_feedback.'><img src="'.$podcast_img.'?e=1460067387&amp;h=f366fdf3732677f4b51da0b8d559221a" />
						   <figcaption class="op-left op-large">
							   <h1>'.$podcast_title.'</h1>
							   <cite>'.$podcast_description.'</cite>
						   </figcaption>
						   <audio autoplay controls><source src="' . $download_link . '" type="audio/mp3"></audio> 
					   	</figure><p>"To experience our fully interactive podcast player, please <a href="http://www.komando.com/listen/episode/'.$matches[1].'"> visit our site.</a>".</p>';
					}
					return $podcast;
				},
				$content
			);
		}

		private function getPodcastWithEpisodeId( $episode_id ) {
			// FIXME: Catch errors, return episode or null and handle a null;
			// Throw a 503 Service Unavailable for the total output to avoid having Facebook Cache bad datas.
			$episode = NULL;
			$json = 'http://podcast.komando.com/episode/'.$episode_id.'.json';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$file_contents = curl_exec($ch);

			if ($file_contents === FALSE) {
				throw new Exception(curl_error($ch));
			}else{
				$episode = json_decode($file_contents);
			}

			curl_close($ch);
			return $episode;
		}

		/**
		 * buildFigureElementAttributes function.
		 * @return feedback
		 */
		private function buildFigureElementAttributes() {
			$feedback = array();
			$data_feedback = '';

			if ( InstantArticlesOptions::getAreLikesOnMediaAllowed() ) {
				$feedback[] = 'fb:likes';
			}

			if ( InstantArticlesOptions::getAreCommentsOnMediaAllowed() ) {
				$feedback[] = 'fb:comments';
			}

			if (!empty($feedback)) {
				$comma_separated = implode(',', $feedback);
				$data_feedback = ' data-feedback="'.$comma_separated.'"';
			}
			return $data_feedback;
		}

	}
}