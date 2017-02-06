<?php
function add_ssb_subscribers_list_menu(){
	?>
<script type="text/javascript">

		(function($){
    $(document).ready(function() {
    $('.empty_button_form').on('submit',function(){
         
        // Add text 'loading...' right after clicking on the submit button. 
        $('#response').text('Processing'); 
         
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
                if (result == 'success'){
                    $('#response').text(result);  
                }else {
                    $('#response').text(result);
                }
            }
        });
         
        // Prevents default submission of the form after clicking on the submit button. 
        return false;   
    });
});
})(jQuery);

	</script>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<style type="text/css">
	#wpcontent{
		background-color: #fff;
	}

	</style>
	<div style='padding:50px; margin:0 auto; margin-top:50px; font-family:sans-serif,arial;font-size:17px; width:60%;'>
	<?php

		global $wpdb;
		$ssm_db = $wpdb->prefix.'ssb_data';
		$ssm_results = $wpdb->get_results( 
	"
	SELECT *
	FROM $ssm_db
	"
);
		?>

		<table class='w3-table w3-striped w3-bordered w3-card-4'>

			<tr class="w3-red">
				<th>ID</th>
				<th>Email</th>
			</tr>
			<?php foreach ( $ssm_results as $ssm_result ) {
	?>
			<tr>
				<td><?php echo $ssm_result->id; ?></td>
				<td><?php echo $ssm_result->email; ?></td>
			</tr>
<?php } ?>
		</table>


</div>
  <a style='background:#F27935; color:#fff; text-decoration:none;padding:15px;' href="<?php echo plugins_url('/subscriber-list-download.php?download_file=sm_subcribers-list.csv',__FILE__); ?>">DOWNLOAD LIST</a>
  <br>
  <br>
  <form method="post" class="empty_button_form" action="<?php echo plugins_url('/subscriber-list-empty.php',__FILE__); ?>">
  <input type="submit" style='background:#F27935; color:#fff; text-decoration:none;padding:15px;' value="Empty List">
 <p id="response">Note : Deleted email addresses can't be recovered. Backup subscribers data before deleting.</p>
  </form>
  <br>

	<?php
}


?>