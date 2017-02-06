<?php

add_filter('wp_footer',"ssb_bar_html");

function ssb_bar_html(){

		// $optionName = get_option('optionName');
	$ssb_enable = get_option('ssb_enable');
	$ssb_vertical_position = get_option('ssb_vertical_position');
	$ssb_content = get_option('ssb_content');
	$ssb_background_color =get_option('ssb_background_color');
	$ssb_bar_height = get_option('ssb_bar_height');
	$ssb_bar_width = get_option('ssb_bar_width');
	$ssb_bar_position = get_option('ssb_bar_position');
	$ssb_cta_button = get_option('ssb_button_cta');
	$ssb_cta_email = get_option('ssb_email_enable');
	$ssb_gradient_color = get_option('ssb_gradient_color');
	$ssb_cta_button_url = get_option('ssb_cta_button_url');
	$ssb_cta_button_text = get_option('ssb_cta_button_text');
	$ssb_border_color = get_option('ssb_border_color');
	$ssb_border_width = get_option('ssb_border_width');
	$ssb_border_position =get_option('ssb_border_position');
	$ssb_close_position =get_option('ssb_close_position');
	$ssb_show_close_button =get_option('ssb_show_close_button');
	$ssb_bar_text_fontFamily = get_option('ssb_bar_text_fontFamily');
	$ssb_bar_text_size = get_option('ssb_bar_text_size');
	$ssb_bar_template = get_option('ssb_bar_template');
	$ssb_bar_email_placeholder = get_option('ssb_bar_email_placeholder');
	$ssb_bar_email_submit_text = get_option('ssb_bar_email_submit_text');
	$ssb_bar_email_mailchimp_apikey = get_option('ssb_bar_email_mailchimp_apikey');
	$ssb_bar_email_mailchimp_listid = get_option('ssb_bar_email_mailchimp_listid');


	if (get_option('ssb_enable')=='Enable') {
		if (get_option('ssb_bar_template')=='TextOnly') {
			include 'ssb_bar.html';
		}

	else if (get_option('ssb_bar_template')=='EmailOnly') {
		include 'ssb_bar_email.html';
		}
	else if (get_option('ssb_bar_template')=='MailchimpEmailOnly') {
		include 'ssb_bar_mailchimp_email.html';
		}
	include 'ssb_bar_style.php';
		
	}

	?>




	<?php

}

?>