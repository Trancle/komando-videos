<?php



function add_ssb_mailchimp(){
  ?>
  <h3>Mail Chimp</h3>
  <form method="post" action="options.php">
        <?php settings_fields( 'ssb_mailchimp_setting_group' );?>
        <?php do_settings_sections( 'ssb_mailchimp_setting_group' ); ?>
        Enter MailChimp API Key :
    <p><input type='text' placeholder='Your Mailchimp API Key' name='ssb_bar_email_mailchimp_apikey' value='<?php echo get_option('ssb_bar_email_mailchimp_apikey'); ?>' style='width:400px; height:50px;font-size:19px;' required>
      <br>
      <br>
      Enter MailChimp List ID :
      <br>
      <br>
      <input type='text' name='ssb_bar_email_mailchimp_listid' placeholder='Your Mailchimp List ID ' value='<?php echo get_option('ssb_bar_email_mailchimp_listid'); ?>' style='width:400px; height:50px;font-size:19px;' required>
    </p>
    <?php submit_button();?>
  </form>
  <?php
}



?>