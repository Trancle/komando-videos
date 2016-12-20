<?php
/*
Template Name: Search
*/

?>
<html>
<head>
<title>Komando.com Search REsults</title>
<base target="_parent" />

<style>
	@font-face {
		font-family: 'ProximaNovaReg';
		src: url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-RegWeb.eot');
		src: url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-RegWeb.eot') format('embedded-opentype'), url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-RegWeb.woff') format('woff');
		font-weight: normal;
		font-style: normal;
	}

	@font-face {
		font-family: 'ProximaNovaSbold';
		src: url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-SboldWeb.eot');
		src: url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-SboldWeb.eot') format('embedded-opentype'), url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-SboldWeb.woff') format('woff');
		font-weight: normal;
		font-style: normal;
	}

	@font-face {
		font-family: 'ProximaNovaBold';
		src: url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-BoldWeb.eot');
		src: url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-BoldWeb.eot') format('embedded-opentype'), url('http://static.komando.com/websites/common/v2/fonts/ProximaNova-BoldWeb.woff') format('woff');
		font-weight: normal;
		font-style: normal;
	}

	/* global box-sizing */
	*,
	*:after,
	*:before {
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	html { font-size: 62.5%; }

	body {
		margin: 0;
		min-width: 320px;
		font: 16px/16px 'ProximaNovaReg', sans-serif;
		font: 1.6rem/1.6rem 'ProximaNovaReg', sans-serif;
		color: #333333;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		font-smoothing: antialiased;
		overflow-x: hidden;
	}

	body p,
	.content-left ul li,
	.content-full ul li,
	.content-left ol li,
	.content-full ol li {
		line-height: 25px;
		line-height: 2.5rem;
	}

	a {
		display: inline-block;
	}

	.clearfix:before,
	.clearfix:after {
		content: ' '; /* 1 */
		display: table; /* 2 */
	}

	.clearfix:after {
		clear: both;
	}

	.clearfix {
		*zoom: 1;
	}

	.search-result {
		margin: 30px 0;
		display: table;
		width: 100%;
	}

	.search-image {
		padding: 0 20px 0 0;
		display: table-cell !important;
		vertical-align: top;
	}

	.search-image,
	.search-image img {
		width: 130px;
	}

	.search-results-text {
		color: #4d525d;
		display: table-cell;
		font: 15px/18px 'ProximaNovaReg', sans-serif;
		font: 1.5rem/1.8rem 'ProximaNovaReg', sans-serif;
		vertical-align: top;
	}

	.search-results-text h3 {
		font: 20px/20px 'ProximaNovaSbold', sans-serif;
		font: 2rem/2rem 'ProximaNovaSbold', sans-serif;
		margin: 0 0 10px 0;
	}

	.search-results-text h3 a {
		color: #42494c;
		text-decoration: none;
		font-weight: normal;
	}

	.highlight {
		background: #edf6ff;
		color: #002487;
	}

	.result-meta {
		border-top: 1px solid #dbdbdb;
		margin: 8px 0 0 0;
		color: #c5c5c5;
		display: block;
		font-size: 10px;
		font-size: 1rem;
		text-transform: uppercase;
	}

	.result-meta span {
		display: inline-block;
		border-right: 1px solid #dbdbdb;
		padding: 8px;
		vertical-align: top;
	}

	.result-meta span:last-child {
		border: none;
	}

	.result-meta span.search-result-views {
		padding: 0;
	}

	.result-meta span a, .result-meta span a:hover {
		display: inline;
		font-family: 'ProximaNovaSbold', sans-serif;
		color: #c5c5c5;
		text-decoration: none;
	}

	.results-header {
		display: inline-block;
	}

	.results-header h1 {
		display: inline-block;
		font-size: 3rem;
		line-height: 3rem;
		margin: 0;
	}

	.more-results-btn {
		display: block;
		margin: 10px 0 0 0;
		line-height: 40px;
		text-align: center;
		text-decoration: none;
		padding: 0 20px;
		background: #0a88ff;
		color: #ffffff;
		border-radius: 3px;
		transition: background .2s;
		-webkit-transition: background .2s;
		-moz-transition: background .2s;
		-o-transition: background .2s;
	}

	.more-results-btn:hover {
		background: #2e43ff;
	}

	@media screen and (min-width: 600px) {
		.search-image,
		.search-image img {
			width: 180px;
		}
	}
</style>
</head>
<body>
<div class="results-header"><h1>Search results from Komando.com</h1></div>
<?php
$results = mss_search_results();
if(!empty($results)) {

	if ($results['hits'] === "0") {

		echo '<h1>Sorry, no results were found.</h1>';
		echo '<p>Perhaps you misspelled your search query, or need to try using broader search terms.</p>';
		echo '<p>For example, instead of searching for "Apple iPhone 6", try something simple like "iPhone".</p>';
		echo '<h3>Did you get here after clicking on a Komando email newsletter link?</h3>';
		echo '<p>Please copy and paste the article title into the search box above to find the article you\'re looking for.</p>';

	} else {

		$i = 1;
		foreach($results['results'] as $result) {

			$type = $result['type'];
			$teaser = $result['teaser'];
			$findthese = array('#<em>#', '#</em>#');
			$replacewith = array('<span class="highlight">', '</span>');
			$teaser = preg_replace($findthese, $replacewith, $teaser);
			$image = get_the_post_thumbnail($result['id'], 'thumbnail');
			$post_type_obj = get_post_type_object(get_post_type($result['id']));
			$category = get_the_terms($result['id'], $result['type'] . '_categories');
			$post_type_link = preg_replace('/[%](post_id)[%]/', '', get_post_type_archive_link($type));

			echo '<div class="search-result clearfix" data-article-url="' . get_permalink($result['id']) . '" data-article-id="' . $result['id'] . '">';

			if(!empty($image)) {
				echo '<a href="' . get_permalink($result['id']) . '" class="search-image">' . $image . '</a>';
			}

			echo '<div class="search-results-text">';
			echo '	<h3><a href="' . get_permalink($result['id']) . '">' . get_the_title($result['id']) . '</a></h3>';
			echo $teaser;
			echo '<div class="result-meta">';

			if(get_post_type($result['id']) != 'page') {
				echo '<span class="search-result-post-type"><a href="' . $post_type_link . '">' . $post_type_obj->labels->name . '</a>';
				if(!empty($category[0]) && $post_type_obj->labels->name) {
					echo ': <a href="' . get_term_link($category[0]->term_id, $result['type'] . '_categories') . '">' . $category[0]->name . '</a>';
				}
				echo '</span>';
			}

			echo '		<span class="search-result-date">' . date('F j, Y', strtotime($result['date'])) . '</span>';
			echo '      </div>';
			echo '	</div>';
			echo '</div>';

			$i++;
			if($i >= 10) {
				break;
			}
		}

		echo '<a href="http://www.komando.com/?s=' . $_GET["iframe_search"] . '" class="more-results-btn">More search results from Komando.com</a>';
	}

} else {
	echo '<h1>Search is temporarily unavailable.</h1>';
	echo '<h3>Please try your search again in a few minutes.</h3>';
} ?>

<script>
	document.domain = 'komando.com';
</script>
</body>
</html>