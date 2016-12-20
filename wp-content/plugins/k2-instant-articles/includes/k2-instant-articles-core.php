<?php
/**
 * Description of facebook-instant-articles
 * @author Lisdanay Dominguez
 */

namespace K2 {
	class InstantArticlesCore {
		public function __construct(){
			$this->action_dispatcher();
			$this->content_filters = new InstantArticlesFilters();
		}

		/**
		 * action_dispatcher function.
		 * @return void
		 */
		private function action_dispatcher(){
			// Allows us to change the feed name if we want. This is part of the /feed?NAME in the URL
			add_action( 'do_feed_' . get_option('k2ia_feed_key','instant_articles'), array( $this, 'do_feed' ) );
			add_action( 'pre_get_posts', array( $this, 'modify_query') );
		}

		/**
		 * Generate the custom RSS Feed for facebook instant articles.
		 * Called by action 'do_feed_instant_articles'
		 *
		 * @see https://developers.facebook.com/docs/instant-articles/publishing
		 * @return void
		 */
		public function do_feed(){
			$rss_template =K2IA__PLUGIN_DIR . 'templates/feed-instant_articles.php';
			load_template($rss_template);
		}

        /**
         * Modify the query that gets the posts to be shown in the instant articles feed.
         * Called by action 'pre_get_posts'
         * @return void
         */
		public function modify_query($query){
			if ( !is_admin() && $query->is_main_query() && $query->is_feed('instant_articles')) {
				// Set the number of posts to be shown on the feed
				// If the number is not set or returns 0, fall back to the default posts_per_rss option.
				$query->set("posts_per_rss", InstantArticlesOptions::getNumberOfArticles() );

				// Check if on frontend and main query is modified
				$query->set( 'order', 'post_modified DESC' );
				add_filter( 'posts_where', array( $this, 'filter_where') );
			}
		}

       /**
        * Alter where query to limit posts to the last days.
        * Called by action 'modify_query'
        * @return string
        */
		public function filter_where( $where = '' ) {
			global $wpdb;
			// Requirements          
			// Only published articles
			return $where . $wpdb->prepare( " AND $wpdb->posts.post_status = '%s'" ,'publish');
		}

		public static function initialize() {
			new self;
		}
	}
}