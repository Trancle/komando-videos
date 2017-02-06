<?php

//options functionality will be here soon
//Options to add
//    Enable/Disable property
// 1. Width/Height properties
// 2. Color properties  bg/text
// 3. Text input
// 4. Logo placement
// 5. Enable/Disable email subscription field
// 6. Subscription options Database/Mailchimp
// 7. Custom link button
// 8. Enable/Disable  close button property 
// 9. Position of the Bar


register_activation_hook(__FILE__,'ssb_activating_options');
function ssb_activating_options(){
	//add_option('optionName','defaultValue');
	add_option('ssb_enable','Enable'); 	
	add_option('ssb_content','Put Some Content Here!');
	add_option('ssb_background_color','#000');
	add_option('ssb_bar_position','');
	add_option('ssb_button_cta');
	add_option('ssb_email_enable');
	add_option('ssb_gradient_color');
	add_option('ssb_cta_button_url');
	add_option('ssb_cta_button_text','Click Here');
	add_option('ssb_border_color','#000');
	add_option('ssb_border_width','3px');
	add_option('ssb_border_position','top');
	add_option('ssb_close_position','');
	add_option('ssb_show_close_button');
	add_option('ssb_vertical_position','left');
	add_option('ssb_bar_width','5');
	add_option('ssb_bar_height','5');
	add_option('ssb_bar_text_size','14px');
	add_option('ssb_bar_text_fontFamily','arial');
	add_option('ssb_bar_template');
	add_option('ssb_bar_email_placeholder');
	add_option('ssb_bar_email_submit_text');
	
}



add_action('wp_head','ssb_sett_to_head');
function ssb_sett_to_head(){

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
	

}

add_action('admin_init','ssb_reg_sett');
function ssb_reg_sett(){
	//register_setting( 'ssb_setting_group', $option_name);
	register_setting( 'ssb_setting_group', 'ssb_enable');
	register_setting('ssb_setting_group','ssb_content');
	register_setting( 'ssb_setting_group', 'ssb_bar_template');
	register_setting('ssb_setting_group','ssb_background_color');
	register_setting('ssb_setting_group','ssb_bar_height');
	register_setting('ssb_setting_group','ssb_bar_position');
	register_setting('ssb_setting_group','ssb_button_cta');
	register_setting('ssb_setting_group','ssb_email_enable');
	register_setting('ssb_setting_group','ssb_gradient_color');
	register_setting('ssb_setting_group','ssb_cta_button_url');
	register_setting('ssb_setting_group','ssb_cta_button_text');
	register_setting('ssb_setting_group','ssb_border_color');
	register_setting('ssb_setting_group','ssb_border_width');
	register_setting('ssb_setting_group','ssb_border_position');
	register_setting('ssb_setting_group','ssb_close_position');
	register_setting('ssb_setting_group','ssb_show_close_button');
	register_setting('ssb_setting_group','ssb_vertical_position');
	register_setting('ssb_setting_group','ssb_bar_width');
	register_setting('ssb_setting_group','ssb_bar_text_size');
	register_setting('ssb_setting_group','ssb_bar_text_fontFamily');
	register_setting('ssb_setting_group','ssb_bar_email_placeholder');
	register_setting('ssb_setting_group','ssb_bar_email_submit_text');

}

register_activation_hook(__FILE__,'ssb_mailchimp_activating_options');
function ssb_mailchimp_activating_options(){

	add_option('ssb_bar_email_mailchimp_apikey');
	add_option('ssb_bar_email_mailchimp_listid');
}



add_action('wp_head','ssb_mailchimp_sett_to_head');
function ssb_mailchimp_sett_to_head(){

	$ssb_bar_email_mailchimp_apikey = get_option('ssb_bar_email_mailchimp_apikey');
	$ssb_bar_email_mailchimp_listid = get_option('ssb_bar_email_mailchimp_listid');

}

add_action('admin_init','ssb_mailchimp_reg_sett');
function ssb_mailchimp_reg_sett(){
	//register_setting( 'ssb_setting_group', $option_name);

	register_setting('ssb_mailchimp_setting_group','ssb_bar_email_mailchimp_apikey');
	register_setting('ssb_mailchimp_setting_group','ssb_bar_email_mailchimp_listid');

}








 ?>