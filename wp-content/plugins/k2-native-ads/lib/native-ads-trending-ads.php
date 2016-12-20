<?php 

function trending_ad_data($count, $ad = null) {

$ad_name = $ad['advertiser-name'];
$presented_by_text = $ad['presented-by-text'];
$presented_by_link = $ad['presented-by-link'];
$ad_text = $ad['ad-text'];
$ad_link = $ad['ad-link'];
$ad_html = $ad['ad-html'];
$trending_image = $ad['trending-image'];
$ad_start_time = date('m/d/Y H:i', $ad['start']);
$ad_end_time = date('m/d/Y H:i', $ad['end']);
$ad_toggle = (($ad['toggle'] == 1) ? 'checked="checked"' : '');
$link_active = (($ad['active']) ? 1 : 0 );

$ad_box = <<<ADBOX
<table class="widefat manage-menus">
	<tbody>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][advertiser-name]">Advertiser</label>
			</th>
			<td>
				<input name="trending[$count][advertiser-name]" type="text" id="trending[$count][advertiser-name]" value="$ad_name" class="large-text code">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][presented-by-text]">Presented by</label>
			</th>
			<td>
				<input name="trending[$count][presented-by-text]" type="text" id="trending[$count][presented-by-text]" value="$presented_by_text" class="large-text code">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][presented-by-link]">Presented by link URL</label>
			</th>
			<td>
				<input name="trending[$count][presented-by-link]" type="text" id="trending[$count][presented-by-link]" value="$presented_by_link" class="large-text code">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][ad-text]">Ad link text</label>
			</th>
			<td>
				<input name="trending[$count][ad-text]" type="text" id="trending[$count][ad-text]" value="$ad_text" class="large-text code">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][ad-link]">Ad link URL</label>
			</th>
			<td>
				<input name="trending[$count][ad-link]" type="text" id="trending[$count][ad-link]" value="$ad_link" class="large-text code">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][ad-html]">HTML tracking code</label>
			</th>
			<td>
				<textarea name="trending[$count][ad-html]" type="text" id="trending[$count][ad-html]" class="large-text code">$ad_html</textarea>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="upload_image">Enter a URL or upload an image</label>
			</th>
			<td>
				<input class="featured-link-image" type="text" size="36" name="trending[$count][trending-image]" value="$trending_image" />
				<input class="upload_image_button" class="button" type="button" value="Upload Image" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][start]">Start time</label>
			</th>
			<td>
				<input name="trending[$count][start]" type="text" id="trending[$count][start]" class="ad-start-time" value="$ad_start_time">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][end]">End time</label>
			</th>
			<td>
				<input name="trending[$count][end]" type="text" id="trending[$count][end]" class="ad-end-time" value="$ad_end_time">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="trending[$count][toggle]">Schedule on?</label>
			</th>
			<td>
				<input name="trending[$count][toggle]" type="checkbox" id="trending[$count][toggle]" $ad_toggle />
			</td>
		</tr>
		<tr valign="top">
			<td colspan="2">
				<div class="delete-link"><span>Delete Me</span></div>
				<input type="hidden" name="trending[$count][active]" value="$link_active" />
			</td>
		</tr>
	</tbody>
</table>
ADBOX;

	$ad_box = implode("\n", array_map('trim', explode("\n", $ad_box)));
	$ad_box = str_replace(array("\r", "\n"), "", $ad_box);

	return $ad_box;

}

function native_ads_trending_ads() { 

if($_POST == true && $_POST['trending-check'] = 1) {

	foreach($_POST['trending'] as $trending) {
		$trending_data[] = array(
			'advertiser-name' => stripslashes(htmlspecialchars(strip_tags($trending['advertiser-name']))),
			'presented-by-text' => stripslashes(htmlspecialchars(strip_tags($trending['presented-by-text']))),
			'presented-by-link' => stripslashes(htmlspecialchars(strip_tags($trending['presented-by-link']))),
			'ad-text' => stripslashes(htmlspecialchars(strip_tags($trending['ad-text']))),
			'ad-link' => stripslashes(htmlspecialchars(strip_tags($trending['ad-link']))),
			'ad-html' => stripslashes($trending['ad-html']),
			'trending-image' => stripslashes(htmlspecialchars(strip_tags($trending['trending-image']))),
			'start' => strtotime($trending['start']),
			'end' => strtotime($trending['end']),
			'toggle' => (($trending['toggle']) ? 1 : 0),
			'active' => $trending['active']
		);
	}

	update_option('k2_trending_ads', $trending_data);

	echo '<div id="message" class="updated fade below-h2"><p>Data saved.</p></div>';

}
?>
<span class="add"><a class="add-new-h2">Add New Advertisement</a></span>

<form method="post" action="">
<div class="trending-ad-holder">
<?php 
$trending_ads = get_option('k2_trending_ads');

$c = 1;
if(count($trending_ads) > 0) {
	foreach($trending_ads as $ad) {
		echo trending_ad_data($c, $ad);
		$c++;
	}
}
?>
</div>

<input type="hidden" name="trending-check" value="1" />

<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p>
</form>

<script>
jQuery(document).ready(function($) {
	var count = <?php echo $c; ?>;
	$('.add').on('click', function() {
		
		$('.trending-ad-holder').append('<?php echo implode('', explode('\n', trending_ad_data('count'))); ?>'.replace(/count/g, count));
		count++;
		
		$('.delete-link span').on('click', function() {
			$(this).parentsUntil('.trending-ad-holder').remove();
		});
		
		return false;
	});	

	$('.delete-link span').on('click', function() {
		$(this).parentsUntil('.trending-ad-holder').remove();
	});
	
});
</script>

<style>
.delete-link {
	display: block;
	text-align: right;
	line-height: 2.4em;
	border-top: 1px solid #e5e5e5;
}

.delete-link span {
	color: #e8402e;
	text-decoration: underline;
	cursor: pointer;
}

.add {
	right: 20px;
	position: absolute;
	top: 70px;
}

.add .add-new-h2 {
	cursor: pointer;
}

#ui-datepicker-div {
	margin: 0;
}

#ui-datepicker-div {
	font-size: 12px !important;
}
</style>

<?php } ?>