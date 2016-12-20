<?php

trait Kom_Www_Api_Admin
{
	/**
     * Admin page support scripts
     */
	public function admin_page_scripts() {
		if (isset($_GET['page']) && $_GET['page'] == 'kom-www-api-admin') {
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-effects-highlight');
			wp_register_style('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
			wp_enqueue_style('jquery-ui');
			wp_enqueue_script('jquery-ui-sortable');
		}
	}

	/**
     * Admin page
     */
    public function admin_init() 
    { ?>  
        
		<div class="wrap metabox-holder">
			<h2>Show Sponsors</h2><br />
			<?php
			if($_POST && $_POST['sponsor-check'] = 1) {
				foreach($_POST['sponsor'] as $sponsor) {
					$sponsor_data[] = [
						'name' => stripslashes(htmlspecialchars(strip_tags($sponsor['name']))),
						'image' => stripslashes(htmlspecialchars(strip_tags($sponsor['image']))),
						'promo-website' => stripslashes(htmlspecialchars(strip_tags($sponsor['promo-website']))),
						'promo-code' => stripslashes(htmlspecialchars(strip_tags($sponsor['promo-code']))),
						'promo-details' => stripslashes(htmlspecialchars(strip_tags($sponsor['promo-details']))),
						'link' => stripslashes(htmlspecialchars(strip_tags($sponsor['link']))),
						'html-code' => stripslashes($sponsor['html-code']),
					];
				}

				update_option('kom_sponsors', $sponsor_data);

				echo '<div id="message" class="updated notice is-dismissible"><p>Show sponsors updated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
			}
			?>

			<span class="add"><a class="add-new-h2">Add new sponsor</a></span>

			<form method="post" action="">

				<div class="meta-box-sortables ui-sortable sponsor-ad-holder">

					<?php
					$sponsors = get_option('kom_sponsors');

					$c = 0;
					if(is_array($sponsors) && count($sponsors) > 0) {
						foreach($sponsors as $sponsor) {
							echo $this->sponsor_html($c, $sponsor);
							$c++;
						}
					} else {
						echo $this->sponsor_html($c);
						$c++;
					}
					?>

				</div>

				<input type="hidden" name="sponsor-check" value="1" />
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save sponsors and positions"></p>
			</form>
		</div>

	    <script type="text/javascript">
		    jQuery(function($) {
			    var count = <?php echo $c; ?>;
			    var custom_uploader;
			    var active_button;
			    var attachment;

			    function delete_link() {
				    $('.delete-link span').off('click').on('click', function() {
					    $(this).parentsUntil('.sponsor-ad-holder').fadeOut('fast', function() {
						    $(this).remove();
					    });
				    });
			    }

			    function upload_button() {

				    $('.upload_image_button').off('click').on('click', function(e) {
					    active_button = $(this);
					    e.preventDefault();

					    // If the uploader object has already been created, reopen the dialog
					    if (custom_uploader) {
						    custom_uploader.open();
						    return;
					    }

					    // Extend the wp.media object
					    custom_uploader = wp.media.frames.file_frame = wp.media({
						    title: 'Choose Image',
						    button: {
							    text: 'Choose Image'
						    },
						    multiple: false
					    });

					    // When a file is selected, grab the URL and set it as the text field's value
					    custom_uploader.on('select', function() {
						    attachment = custom_uploader.state().get('selection').first().toJSON();
						    $(active_button).parent().find('.sponsor-image').val(attachment.url);
					    });

					    // Open the uploader dialog
					    custom_uploader.open();
				    });
			    }

			    function collapse_links() {
				    $('.handlediv, .hndle').off('click').on('click', function() {
					    if($(this).parent().hasClass('closed')) {
						    $(this).parent().removeClass('closed');
					    } else {
						    $(this).parent().addClass('closed');
					    }
				    });
			    }

			    $('.add').on('click', function() {

				    $('.sponsor-ad-holder').append('<?php echo implode('', explode('\n', $this->sponsor_html('count'))); ?>'.replace(/count/g, count));
				    count++;

				    delete_link();
				    upload_button();
				    collapse_links();

				    return false;
			    });

			    $('.meta-box-sortables').sortable({
				    stop: function() {
					    var inputs = $('input.currentposition');
					    var nbElems = inputs.length;
					    $('input.currentposition').each(function(i) {
						    $(this).val(i);
					    });
				    }
			    });

			    delete_link();
			    upload_button();
			    collapse_links();

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
			    top: 40px;
		    }

		    .add .add-new-h2 {
			    cursor: pointer;
		    }
	    </style>

<?php }

	private function sponsor_html($count, $sponsor = null)
	{
		$sponsor_exist = ((is_array($sponsor)) ? 'closed' : '');
		$sponsor_header = (($sponsor['name']) ? $sponsor['name'] : 'New sponsor');
		$sponsor_name = $sponsor['name'];
		$sponsor_image = $sponsor['image'];
		$sponsor_promo_website = $sponsor['promo-website'];
		$sponsor_promo_code = $sponsor['promo-code'];
		$sponsor_promo_details = $sponsor['promo-details'];
		$sponsor_link = $sponsor['link'];
		$html_code = $sponsor['html-code'];

$sponsor_html = <<<HTML
	<div class="postbox $sponsor_exist" id="link$count">
		<div class="handlediv"><br></div>
		<h3 class="hndle"><span>$sponsor_header</span></h3>
		<div class="inside">

			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row"><label for="sponsor[$count][name]">Sponsor name</label></th>
					<td><input name="sponsor[$count][name]" type="text" id="sponsor[$count][name]" value="$sponsor_name" class="large-text code" placeholder="eg. Carbonite"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sponsor[$count][image]">Enter a URL or upload an image</label></th>
					<td>
						<input class="sponsor-image" id="sponsor[$count][image]" type="text" size="36" name="sponsor[$count][image]" value="$sponsor_image" />
						<input class="upload_image_button button" type="button" value="Upload Image" />
						<p><em>Image size 405x150</em></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sponsor[$count][promo-website]">Sponsor website</label></th>
					<td><input name="sponsor[$count][promo-website]" type="text" id="sponsor[$count][promo-website]" value="$sponsor_promo_website" class="large-text code" placeholder="eg. www.carbonite.com/kim"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sponsor[$count][promo-code]">Sponsor promo code</label></th>
					<td><input name="sponsor[$count][promo-code]" type="text" id="sponsor[$count][promo-code]" value="$sponsor_promo_code" class="large-text code" placeholder="eg. Promo code: KIM"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sponsor[$count][promo-details]">Sponsor promo details</label></th>
					<td><input name="sponsor[$count][promo-details]" type="text" id="sponsor[$count][promo-details]" value="$sponsor_promo_details" class="large-text code" placeholder="eg. Earn two months free with a 15-day free trial!"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sponsor[$count][link]">Sponsor link</label></th>
					<td><input name="sponsor[$count][link]" type="text" id="sponsor[$count][link]" value="$sponsor_link" class="large-text code" placeholder="eg. http://goo.gl/j23CS5"></td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="sponsor[$count][html-code]">HTML tracking code</label>
					</th>
					<td>
						<textarea name="sponsor[$count][html-code]" id="sponsor[$count][html-code]" class="large-text code">$html_code</textarea>
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<div class="delete-link"><span>Delete Me</span></div>
					</td>
				</tr>
				</tbody>
			</table>

			<input type="hidden" name="sponsor[$count][sponsor-position]" class="currentposition" value="$count" />
			<input type="hidden" class="savedposition" value="$count" />
		</div>
	</div>
HTML;

		$sponsor_html = preg_replace('/\t/', '', $sponsor_html);
		$sponsor_html = preg_replace('/(\r(\n)?|(\r)?\n)/', '', $sponsor_html);

		return $sponsor_html;
	}
}