<?php
namespace K2 {
	class InstantArticlesOptions {
		private static $_num_articles_var_name = "k2ia_articles_num";
		private static $_are_media_comments_allowed_name = "k2ia_comment_media";
		private static $_are_media_likes_allowed_name = "k2ia_like_media";
		private static $_ad_unit_configuration_values = "k2ia_ads";
		private static $_total_paragraph_per_ads = "k2ia_paragraph_per_ads";

		/* Get Number of Articles
		 *
		 * Database backed configuration to indicate how many article we will render in the RSS feed
		 *
		 * @return number of articles to show in RSS
		*/
		public static function getNumberOfArticles(){
			$val = get_option(self::$_num_articles_var_name) ;
			if( empty($val) ) {
				$ret = 50;
			}else {
				$ret = intval( $val );
			}
			return min(200, $ret);
		}
		public static function getAreLikesOnMediaAllowed() {
			return get_option(self::$_are_media_likes_allowed_name);
		}

		public static function getAreCommentsOnMediaAllowed() {
			return get_option(self::$_are_media_comments_allowed_name);
		}

		public static function getAdUnitConfigurationValues() {
			return get_option(self::$_ad_unit_configuration_values);
		}

		public static function getTotalParagraphPerAd() {
			$val = get_option(self::$_total_paragraph_per_ads);
			if( empty($val) ) {
				return 7;
			}else {
				$val = intval( $val );
				return ( $val > 0 ) ? $val : 7;
			}
		}

		/* Set Number of Articles */
		public static function setNumberOfArticles($num){
			set_option( self::$_num_articles_var_name, $num );
		}

		public static function setAreLikesOnMediaAllowed($opt) {
			set_option(self::$_are_media_likes_allowed_name,$opt);
		}

		public static function setAreCommentsOnMediaAllowed($opt) {
			set_option(self::$_are_media_comments_allowed_name,$opt);
		}

		public static function setAdUnitConfigurationValues($obj) {
			set_option(self::$_ad_unit_configuration_values,$obj);
		}
		public static function setTotalParagraphPerAd($cant) {
			set_option(self::$_total_paragraph_per_ads,$cant);
		}
	}
}