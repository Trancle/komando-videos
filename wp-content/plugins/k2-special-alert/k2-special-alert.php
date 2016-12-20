<?php
/*
Plugin Name: K2 Special Alert
Plugin URI: http://www.komando.com
Description: Allows a special alert message to scroll across the top of the home page.
Author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

add_action('admin_menu', 'k2_special_alert_page_menu');
function k2_special_alert_page_menu() {
    add_menu_page('Special Alert', 'Special Alert', 'edit_others_posts', 'special-alert-settings', 'k2_special_alert_page');
}

function k2_special_alert_page() { 

	if($_POST['check'] == '1') {

		$message = preg_replace(array('(<br \/>)', '(<br\/>)', '(<br>)'), ' ', $_POST['special-alert-message']);
		$message = strip_tags($message);
		$message = preg_replace('/(\s)\s+/', ' ', $message);
		$message = stripslashes($message);
		if(isset($_POST['special-alert-toggle'])) {
			$active = '1';
		} else {
			$active = '0';
		}

		$data = array(
			'message' => $message,
			'link' => $_POST['special-alert-link'],
			'active' => $active
			);
		update_option('special_alert_data', $data);
	}

	$data = get_option('special_alert_data');
	
	if(isset($data['link'])) {
		$link = $data['link'];
	} else {
		$link = 'http://';
	}

	if($data['active'] == '1') {
		$active = 'checked="checked"';
	} else {
		$active = '';
	}

	?>
	<div class="wrap">
		<?php screen_icon('options-general'); ?> <h2>Special Alert Settings</h2>

		<form method="post" action="">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="special-alert-message">Alert Message</label></th>
						<td><textarea name="special-alert-message" type="text" id="special-alert-message" class="large-text code"><?php echo $data['message'] ?></textarea></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="special-alert-link">Alert Link</label></th>
						<td><input name="special-alert-link" type="text" id="special-alert-link" value="<?php echo $link ?>" class="large-text code"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="special-alert-toggle">Alert On?</label></th>
						<td><input name="special-alert-toggle" type="checkbox" id="special-alert-toggle" <?php echo $active ?>></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="check" value="1" />
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		</form>
	</div>
<?php }

function k2_special_alert() { 

	$data = get_option('special_alert_data');

	if($data['active'] == '1') {
	?>
	<div id="special-alert">
		<div class="alert-cover-left"></div>
		<div class="alert-cover-right"></div>
		<a href="<?php echo $data['link'] ?>">
			<div class="alert-bg">
				<ul class="alert-message">
					<li class="alert-text"><span>// Special Alert //</span><?php echo $data['message'] ?></li>
				</ul>
			</div>
		</a>
	</div>
	<script>
	jQuery(document).ready(function($) {

		$('.alert-text').clone().appendTo('.alert-message').clone().appendTo('.alert-message').clone().appendTo('.alert-message');
		var speed = 5;
		var items; 
		var scroller = $('.alert-message');
		var width = 0;

		$(window).on('resize', function() {
			
			scroller.children().each(function() {
				width += $(this).outerWidth(true);
			});
			
			scroller.css('width', width);
			scroll();
			
			function scroll() {
				items = scroller.children();
				var scrollWidth = items.eq(0).outerWidth();
				scroller.animate({'left' : 0 - scrollWidth}, scrollWidth * 100 / speed, 'linear', changeFirst);
			}
			
			function changeFirst() {
				scroller.append(items.eq(0).remove()).css('left', 0);
				scroll();
			}

		}).trigger('resize');

		$('#special-alert a').on('click', function(e) {
			e.preventDefault();
			ga('send', {
	            'hitType': 'event',
	            'eventCategory': 'Homepage',
	            'eventAction': 'click',
	            'eventLabel': 'Special alert link',
	            'nonInteraction': 1,
	            'hitCallback': function() {
	                console.log('GA: recorded link event, redirecting');
					window.location.href = "<?php echo $data['link'] ?>";
	            }
        	});
		});

	})
	</script>
	<?php } 
	}

?>