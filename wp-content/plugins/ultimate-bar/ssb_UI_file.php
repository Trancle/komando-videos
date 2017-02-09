<?php
//UI will go here

function ssb_bar_plugin_ui(){ //UI function

	?>
	<?php settings_fields( 'ssb_setting_group' );?>
	<?php do_settings_sections( 'ssb_setting_group' );
	
	?>


<div class='formLayout'>
<br>
<h1 class='ub-heading-bar' style='background:#0074A2;'>Ultimate Bar Plugin</h1>
<br>
<p class="ub_p"> Unlock Subscribe form bar, MailChimp bar and more awesome features here : <a href="http://web-settler.com/ultimate-bar/">Ultimate Bar Premium</a></p>
<br>
<p class='ub_p'>Need help ?  contact us  : <span style='color:#0074A2;'> support@web-settler.com</span></p>
<br>

<p class="ub_p">If you want us to design your Bar Mail us : <a >admin@web-settler.com</a> </p>
<br>
<form action="options.php" method="post" >

	<?php settings_fields( 'ssb_setting_group' );?>
	<?php do_settings_sections( 'ssb_setting_group' );?>
	<h1 class='ub-heading-bar'>Bar Settings</h1>
	<br>
	<label for="ssb_enable">Enable/Disable Bar : </label>
	<div class="switch">
  <input id="cmn-toggle-4" class="cmn-toggle cmn-toggle-round-flat" name="ssb_enable" type="checkbox" value='Enable' <?php checked('Enable',get_option('ssb_enable'));  ?> /> 
    <label for="cmn-toggle-4"></label>
</div>
	<p class='field_desc'> A quick way to completely disable the bar.</p>
	<br>
	<label for="ssb_show_close_button">Hide Bar Close Button : </label>
	<div class="switch-2">
  <input id="cmn-toggle-3" class="cmn-toggle cmn-toggle-round-flat" name="" type="checkbox" value="1" disabled
	<?php checked('Enable',get_option('ssb_show_close_button')); ?> /> 
    <label for="cmn-toggle-3"></label>
</div>
	<span class='premium_v_link'> <a href="http://web-settler.com/ultimate-bar/" target="_blank"> (Premium version Only) </a></span>
	<p class='field_desc'>Hide bar close button to keep it always visible.</p>
	<br>
	<label for="ssb_show_close_button">Hide Bar Logo : </label>
	<div class="switch-2">
  <input id="cmn-toggle-3" class="cmn-toggle cmn-toggle-round-flat" name="" type="checkbox" value="1" disabled
	<?php checked('Enable',get_option('ssb_show_close_button')); ?> /> 
    <label for="cmn-toggle-3"></label>
</div>
  <span class='premium_v_link'> <a href="http://web-settler.com/ultimate-bar/" target="_blank"> (Premium version Only) </a></span>
	<p class='field_desc'>Hide bar logo.</p>
	<br>
	<label for="ssb_bar_position"> Select Bar Position :</label>
	<select name="ssb_bar_position">
		<option value="top:0px;"
		<?php selected('top:0px;',get_option('ssb_bar_position')); ?>	> Top </option>
		<option value="bottom:0px;"
		<?php selected('bottom:0px;',get_option('ssb_bar_position')); ?>	> Bottom </option>
	</select>
	<br>
	<h1 class='ub-heading-bar'>Bar Design</h1>
	<br>
	<label for='ssb_background_color'>Select Bar Color : </label>
	<input class='ub_color_picker' type="text" name="ssb_background_color" value="<?php echo get_option('ssb_background_color'); ?>">
	<br>
	<label for='ssb_bar_height'>Set Bar Padding (px) :</label>
	<input type="number" name='ssb_bar_height' value='<?php echo get_option('ssb_bar_height'); ?>' min='5' max='50' placeholder='Enter value in px'>
	<br>
	<label for="ssb_bar_text_size">Set Font Size (px): </label>
	<input type="number" name="ssb_bar_text_size" value="<?php echo get_option('ssb_bar_text_size'); ?>">
	<br>
	<label for="ssb_bar_text_fontFamily">Set Font Family : </label>
	<input type="text" name="ssb_bar_text_fontFamily" value="<?php echo get_option('ssb_bar_text_fontFamily'); ?>">
	<br>
	<h1 class='ub-heading-bar'>Bar Layout</h1>
	<br>
	<label for="ssb_bar_template">Select Bar Layout :</label>
	<br>
	Text Only Bar 
	<input id='ssbTextOnlySelect' style='margin-left:300px;' class="checkbox" type="radio" name="ssb_bar_template" value="TextOnly"
	<?php checked('TextOnly',get_option('ssb_bar_template')); ?> >
	<p class='field_desc'>This bar will only display text.</p>
	<br>
	Text With Email Subscribe Field 
	<input id='ssbEmailOnlySelect' style='margin-left:300px;' class="checkbox" id='emailBar_radio' type="radio" name="ssb_bar_template" value=""
	  disabled > <i><span class='premium_v_link'><a href="http://web-settler.com/ultimate-bar/" target="_blank"><a href="http://web-settler.com/ultimate-bar/" target="_blank">(Premium version Only)</a></a></span>
	<p class='field_desc'>This bar will display text, HTML, and Subscribe form.</p>
	<br>
	Text With MailChimp Email Subscribe Field 
	<input id='ssbEmailOnlySelect' style='margin-left:300px;' class="checkbox" id='emailBar_radio' type="radio" name="ssb_bar_template" value=""
	 disabled > <span class='premium_v_link'><a href="http://web-settler.com/ultimate-bar/" target="_blank">(Premium version Only)</a></span>
	<p class='field_desc'>This bar will display text, HTML, and MailChimp Subscribe form.</p>
	<br>
	<br>
	<h1 class='ub-heading-bar'>Email Settings</h1>
	<br>
	<br>
	<label class='ssb_email_fields'>Email Field Place Holder :</label>
	<input type="text" disabled class='ssb_email_fields' name="ssb_bar_email_placeholder" value="<?php echo get_option('ssb_bar_email_placeholder'); ?>"> <span class='premium_v_link'><a href="http://web-settler.com/ultimate-bar/" target="_blank">(Premium version Only)</a></span><br>
	<p class='field_desc'>Add text to display placeholder (Faded text) in email field of subscribe form.</p>
	<br>
	<label class='ssb_email_fields' >Email Field Submit Button Text : </label>
	<input type="text"  disabled class='ssb_email_fields' name="ssb_bar_email_submit_text" value="<?php echo get_option('ssb_bar_email_submit_text'); ?>"> <span class='premium_v_link'><a href="http://web-settler.com/ultimate-bar/" target="_blank">(Premium version Only)</a></span></i><br>
	<p class='field_desc'>Add text to display in button of subscribe form.</p>
	<br>
	<h1 class='ub-heading-bar'>Bar Content</h1>
	<br>
	<label for="ssb_content">Add Bar Text : </label>
	<!-- <textarea name="ssb_content" placeholder='Enter Some text' width='200px' cols='20' rows="10" value="<?php //echo get_option('ssb_content'); ?>"><?php //echo get_option('ssb_content'); ?></textarea> -->
	<br>
	<?php $settings = array('media_buttons'=> false,'ssb_content','textarea_rows'=>5);
   $ssb_content = get_option('ssb_content');
   wp_editor($ssb_content,'ssb_content',$settings); ?>

	<br/>
	<?php submit_button('Save Changes');  ?>		

</form>
<a href="http://web-settler.com/ultimate-bar/" style='text-decoration: none;' target='_blank' onclick="this.parentNode.submit(); return false;"><div id='rate_button' style=''>Get Premium Version</div></a>
</div>
<style type="text/css">
#rate_button{
		text-align: center;
		padding:8% 5% 8% 5%;
		background:#FFA635;font-size:22px;border:none;color:#fff; border-bottom:10px solid #E08A1C;
		text-decoration: none;
		border-radius: 10px;
		margin-top: 22px;
		font-size: 40px;
	}
	#rate_button:hover{
		background: #FF9918;

	}
	#rate_button:active{
		border: none;
		padding-top: 9%;
	}
	.formLayout{
		background:#fff;

	}
	.ub_p{
		font-size: 20px;
		font-family: arial;
		color: #525252;
		font-weight: bold;
		margin-left: 20px;
	}
	.ub-heading-bar{
		background-color: #FFBA00;
		padding:25px 30% 25px 30%;
		text-align: center;
		color: #fff;
		font-size: 38px;
		line-height: 25px;
	}

	
	.formLayout
    {
        
        padding: 10px;
        width: 90%;
        margin: 10px;
        margin-left:4%;
    }
    
    .formLayout label 
    {
        display: block;
        width: 260px;
        float: left;
        margin-bottom: 30px;
        margin-left: 20px;
    }
    .formLayout input,select{
        display: block;
        width: 200px;
        float: left;
        margin-bottom: 30px;

    }

     .formLayout textarea{
        float: left;
        margin-bottom: 10px;

    }
 
    .formLayout label
    {
        text-align: right;
        padding-right: 20px;
        font-size: 16px;
        font-weight: bold;
    }
 
    br
    {
        clear: left;
    }

    .checkbox , .radio{
    	width:15px !important;
    	height: 15px !important;
    }
    .premium_v_link{
    	font-size: 18px;
    }
    .field_desc{
    	color:#636363;
    	font-style: italic;
    	font-size: 12px;
    }

    .cmn-toggle {

  position: absolute;
  margin-left: -9999px;
  visibility: hidden;
}
.cmn-toggle + label {
  display: block;
  position: relative;
  cursor: pointer;
  outline: none;
  user-select: none;
}

    input.cmn-toggle-round-flat + label {
  padding: 2px;
  width: 120px;
  height: 20px;
  background-color: #dddddd;
  border-radius: 60px;
  transition: background 0.4s;
}
input.cmn-toggle-round-flat + label:before,
input.cmn-toggle-round-flat + label:after {
  display: block;
  position: absolute;
  content: "";
}
input.cmn-toggle-round-flat + label:before {
  top: 2px;
  left: 2px;
  bottom: 2px;
  right: 2px;
  background-color: #fff;
  border-radius: 60px;
  transition: background 0.4s;
}
input.cmn-toggle-round-flat + label:after {
  top: 4px;
  left: 4px;
  bottom: 4px;
  width: 52px;
  background-color: #dddddd;
  border-radius: 52px;
  transition: margin 0.4s, background 0.4s;
}
input.cmn-toggle-round-flat:checked + label {
  background-color: #8ce196;
}
input.cmn-toggle-round-flat:checked + label:after {
  margin-left: 60px;
  background-color: #8ce196;
}



    
</style>


<?php

}




 ?>