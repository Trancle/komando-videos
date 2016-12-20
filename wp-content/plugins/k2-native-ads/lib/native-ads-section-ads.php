<?php function native_ads_section_ads() {
if($_POST == true && $_POST['section-check'] = 1) {

	foreach($_POST['section'] as $section) {
		$section_data[] = array(
			'section' => stripslashes(htmlspecialchars(strip_tags($section['section']))),
			'section_pretty' => $section['section_pretty'],
			'advertiser' => stripslashes(htmlspecialchars(strip_tags($section['advertiser']))),
			'text' => stripslashes(htmlspecialchars(strip_tags($section['text']))),
			'link' => stripslashes(htmlspecialchars(strip_tags($section['link']))),
			'image' => $section['image'],
			'start' => strtotime($section['start']),
			'end' => strtotime($section['end']),
			'toggle' => (($section['toggle']) ? 1 : 0),
			'active' => $section['active']
		);
	}

	update_option('k2_section_ads', $section_data);

	echo '<div id="message" class="updated fade below-h2"><p>Data saved.</p></div>';
}

function section_ad_data($ad) {

$section = $ad['section'];
$section_pretty = $ad['section_pretty'];
$advertiser = $ad['advertiser'];
$text = $ad['text'];
$link = $ad['link'];
$image = $ad['image'];
$start = date('m/d/Y H:i', $ad['start']);
$end = date('m/d/Y H:i', $ad['end']);
$toggle = (($ad['toggle'] == 1) ? 'checked="checked"' : '');
$link_active = (($ad['active']) ? 1 : 0 );

$ad = <<<ADBOX
<table class="widefat manage-menus $section">
	<tbody>
		<tr valign="top">
			<th scope="row" colspan="2"><h2>$section_pretty</h2></th>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="section[$section][advertiser]">Advertiser</label></th>
			<td><input name="section[$section][advertiser]" type="text" id="section[$section][advertiser]" value="$advertiser" class="large-text code"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="section[$section][text]">Ad link text</label></th>
			<td><input name="section[$section][text]" type="text" id="section[$section][text]" value="$text" class="large-text code"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="section[$section][link]">Ad link URL</label></th>
			<td><input name="section[$section][link]" type="text" id="section[$section][link]" value="$link" class="large-text code"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="upload_image">Enter a URL or upload an image</label></th>
			<td>
				<input class="featured-link-image" type="text" size="36" name="section[$section][image]-apps" value="$image" /> 
				<input class="upload_image_button" class="button" type="button" value="Upload Image" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="section[$section][start]">Start time</label></th>
			<td><input name="section[$section][start]" type="text" id="section[$section][start]" class="ad-start-time" value="$start"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="section[$section][end]">End time</label></th>
			<td><input name="section[$section][end]" type="text" id="section[$section][end]" class="ad-end-time" value="$end"></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="section[$section][toggle]">Schedule on?</label></th>
			<td>
				<input name="section[$section][toggle]" type="checkbox" id="section[$section][toggle]" $toggle>
				<input type="hidden" name="section[$section][active]" value="$link_active" />
				<input type="hidden" name="section[$section][section]" value="$section" />
				<input type="hidden" name="section[$section][section_pretty]" value="$section_pretty" />
			</td>
		</tr>
	</tbody>
</table>
ADBOX;

return $ad;

}

?>

<div id="message-cache" class="updated fade below-h2" style="display:none;"><p>Homepage cache has been terminated. It'll be back though.</p></div>

<form method="post" action="">
	<div class="section-ad-holder">
	<?php 
	$section_ads = get_option('k2_section_ads');

	if(empty($section_ads)) {
		$section_ads = array(
			array('section' => 'apps', 'section_pretty' => 'Apps', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'buying_guides', 'section_pretty' => 'Buying Guides', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'lactive' => ''),
			array('section' => 'columns', 'section_pretty' => 'Columns', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'cool_sites', 'section_pretty' => 'Cool Sites', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'downloads', 'section_pretty' => 'Downloads', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'small_business', 'section_pretty' => 'Small Business', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'tips', 'section_pretty' => 'Tips', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'happening_now', 'section_pretty' => 'Happening Now', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => ''),
			array('section' => 'new_technologies', 'section_pretty' => 'New Technologies', 'advertiser' => '', 'text' => '', 'link' => '', 'image' => '', 'start' => '', 'end' => '', 'toggle' => '', 'active' => '')
		);
	}

	foreach($section_ads as $ad) {
		echo section_ad_data($ad);
	}
	?>

	<input type="hidden" name="section-check" value="1" />
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p>
	</div>
</form>

<style>
#ui-datepicker-div {
	margin: 0;
}

#ui-datepicker-div {
	font-size: 12px !important;
}
</style>
<?php } ?>