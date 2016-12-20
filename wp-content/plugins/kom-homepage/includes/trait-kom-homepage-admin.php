<?php

trait Kom_Homepage_Admin
{
	/**
     * Admin page support scripts
     */
	public function admin_page_scripts() {
		if (isset($_GET['page']) && $_GET['page'] == 'kom-homepage-admin') {
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-effects-highlight');
			wp_register_style('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
			wp_enqueue_style('jquery-ui');
			wp_enqueue_script('jquery-ui-sortable');

			wp_register_script('kom-homepage-featured-links-js', KOM_HOMEPAGE_URL . '/js/kom-homepage-featured-links.js', array('jquery'));
			wp_enqueue_script('kom-homepage-featured-links-js');

			wp_register_script('jquery-ui-timepicker-addon', KOM_HOMEPAGE_URL . '/js/jquery-ui-timepicker-addon.js', array('jquery-ui-datepicker'));
			wp_enqueue_script('jquery-ui-timepicker-addon');
		}
	}

	public function fetch_post_data() {
		$id = $_GET['id'];
		if(empty($id)) {
			die();
		}

		if(!get_post_status($id)) {
			
			$data = array(
				'text' => 'noid'
			);

			$data = json_encode($data);
			echo $data;
			die();

		} else {

			$post = get_post($id);
			$image_id = get_post_thumbnail_id($post->ID);
			$large_image = substr(wp_get_attachment_image_src($image_id, 'large')[0], 5);
			if($large_image) { 
				$image = $large_image;
			} else {
				$image = '';
			}

			$data = array(
				'text' => get_the_title($post->ID),
				'link' => substr(get_permalink($post->ID), 5),
				'image' => $image
			);

			$data = json_encode($data);
			echo $data;
			die();
		}
	}

	/**
     * Admin page
     */
    public function admin_init() 
    { ?>  
        
		<div class="wrap metabox-holder">
		<h2>Home Featured Links (Kim's Desk) <div class="button" id="k2-clear-homepage-cache">Clear homepage cache</div></h2><br />
		<?php 

		if ($_POST == true && $_POST['check'] = 1) {

			foreach ($_POST['link'] as $link) {

                if (preg_match('#^https?:\/\/#', $link['featured-link-url'])) {
                    $new_link = $link['featured-link-url'];
                } else {
                    $new_link = preg_replace('#^.*:?\/\/#i', '//', $link['featured-link-url']);
                }
				
				$link = [
					'text' => stripslashes(preg_replace('/(\s)\s+/', ' ', strip_tags(preg_replace(array('(<br \/>)', '(<br\/>)', '(<br>)'), ' ', $link['featured-link-text'])))),
					'link' => $new_link,
					'image' => $link['featured-link-image'],
					'start' => strtotime($link['featured-link-start']),
					'end' => strtotime($link['featured-link-end']),
					'scheduled' => ($link['featured-link-toggle'] ? 1 : 0),
					'active' => $link['featured-link-active'],
					'position' => $link['featured-link-position'],
				];

				$links[] = $link;
			}

			usort($links, function($a, $b) {
				return $a['position'] - $b['position'];
			});

			update_option('kom_homepage_custom_links', $links);
			self::clear_cache();
			self::cron();

			echo '<div id="message" class="updated fade below-h2"><p>Data saved.</p></div>';
		}

		$links = get_option('kom_homepage_custom_links');
		?>

		<div id="message-cache" class="updated fade below-h2" style="display:none;"><p>Homepage cache has been terminated. It'll be back though.</p></div>

		<form method="post" action="">

			<div class="meta-box-sortables ui-sortable section-ad-holder">
				
				<?php 

				$i = 0;
				foreach ($links as $link) {

					$scheduled = $active_title = $scheduled_title = $image_loc = $image_data = $image_error = $image_error_simple = null;

					if($link['scheduled']) {
						$scheduled = 'checked="checked"';
					}

					if($link['active'] && $link['scheduled']) {
						$active_title = ' <span class="link-running"> Running (ends at ' . date('m/d/Y H:i', $link['end']) . ')</span>';
					} else if($link['scheduled'] && empty($link['active'])) {
						$scheduled_title = ' <span class="link-scheduled"> Scheduled (starts at ' . date('m/d/Y H:i', $link['start']) . ')</span>';
					}

					$image_loc = parse_url($link['image']);
					$image_data = getimagesize('..' . $image_loc['path']);

					if(!empty($image_data) && ($image_data[0] < 970 || $image_data[1] < 546)) {
						$image_error = '<div class="error inline-error fade below-h2"><p>The image must be at least 970x546. The one selected is ' . $image_data[0] . 'x' . $image_data[1] . '.</p></div>';
						$image_error_simple = ' <span class="image-error">Image error!</span>';
					} 
					
					?>

					<div class="postbox closed" id="link<?php echo $i; ?>">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span><?php echo substr($link['text'], 0, 30); ?>...</span><?php if($scheduled_title) { echo $scheduled_title; } ?><?php if($active_title) { echo $active_title; } ?><?php if($image_error_simple) { echo $image_error_simple; } ?></h3>
						<div class="inside">
							<?php if($image_error) { echo $image_error; } ?>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fetch-post-data">Fetch content by ID</label></th>
										<td>
											<input class="fetch-content-data" type="text" size="36" name="fetch-post-data" placeholder="Numbers only" /> 
											<input class="fetch-article-data-button" class="button" type="button" value="Fetch Article" />
											<input class="fetch-video-data-button" class="button" type="button" value="Fetch Video" />
											<p class="fetch-content-status"></p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="link[<?php echo $i; ?>][featured-link-text]">Featured link text</label></th>
										<td><input name="link[<?php echo $i; ?>][featured-link-text]" type="text" id="link[<?php echo $i; ?>][featured-link-text]" value="<?php echo $link['text']; ?>" class="large-text code" placeholder="eg. 5 Facebook settings to change now"></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="link[<?php echo $i; ?>][featured-link-url]">Featured link URL</label></th>
										<td><input name="link[<?php echo $i; ?>][featured-link-url]" type="text" id="link[<?php echo $i; ?>][featured-link-url]" value="<?php echo $link['link']; ?>" class="large-text code" placeholder="eg. http://www.komando.com/columns/271558/5-facebook-settings-to-change-now"></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="link[<?php echo $i; ?>][featured-link-image]">Enter a URL or upload an image</label></th>
										<td>
											<input class="featured-link-image" id="link[<?php echo $i; ?>][featured-link-image]" type="text" size="36" name="link[<?php echo $i; ?>][featured-link-image]" value="<?php echo $link['image']; ?>" /> 
											<input class="upload_image_button" class="button" type="button" value="Upload Image" />
											<p><em>Minimum size 970x546</em></p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="link[<?php echo $i; ?>][featured-link-start]">Start time</label></th>
										<td><input name="link[<?php echo $i; ?>][featured-link-start]" type="text" id="link[<?php echo $i; ?>][featured-link-start]" class="ad-start-time" value="<?php echo date('m/d/Y H:i', $link['start']); ?>"></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="link[<?php echo $i; ?>][featured-link-end]">End time</label></th>
										<td><input name="link[<?php echo $i; ?>][featured-link-end]" type="text" id="link[<?php echo $i; ?>][featured-link-end]" class="ad-end-time" value="<?php echo date('m/d/Y H:i', $link['end']); ?>"></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="link[<?php echo $i; ?>][featured-link-toggle]">Schedule on?</label></th>
										<td><input name="link[<?php echo $i; ?>][featured-link-toggle]" class="featured-link-checkbox" type="checkbox" id="link[<?php echo $i; ?>][featured-link-toggle]" <?php if($scheduled) { echo $scheduled; } ?>></td>
									</tr>
								</tbody>
							</table>
							<input type="hidden" name="link[<?php echo $i; ?>][featured-link-active]" value="<?php if($link['active']) { echo 1; } else { echo 0; } ?>" />
							<input type="hidden" name="link[<?php echo $i; ?>][featured-link-position]" class="currentposition" value="<?php echo $i; ?>" />
							<input type="hidden" class="savedposition" value="<?php echo $i; ?>" />
							<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save all"></p>
						</div>
					</div>

					<?php

					$i++;
				} 

				?>
				
			</div>

			<input type="hidden" name="check" value="1" />
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save all"></p>
		</form>
	</div>

	<style>
	.image-error {
		color: #dd3d36;
		margin: 0 0 0 20px;
	}

	.inline-error {
		background: #ffeaea !important;
		color: #dd3d36 !important;
	}

	.link-running {
		color: #17bf2b !important;
	}

	.link-scheduled {
		color: #1d62cb !important;
	}

	#ui-datepicker-div {
		margin: 0 !important;
	}

	.fetch-content-status {
		display: none;
		font-style: italic;
	}

	.fetch-content-status.good {
		color: #0bad12;
	}

	.fetch-content-status.bad {
		color: #e40f0f;
	}
	</style>

	<script type="text/javascript">
	jQuery(document).ready(function($) {

		$('.meta-box-sortables').sortable({
			stop: function() {
				var inputs = $('input.currentposition');
				var nbElems = inputs.length;
				$('input.currentposition').each(function(idx) {
					$(this).val(idx);
				});
			}
		});

		$('.handlediv, .hndle').on('click', function() { 
			if($(this).parent().hasClass('closed')) {
				$(this).parent().removeClass('closed');
			} else {
				$(this).parent().addClass('closed');
			}
		});

		var ajaxurl = "<?php echo get_template_directory_uri() . '/k2-ajax.php'; ?>"; 

		$('#k2-clear-homepage-cache').on('click', function() {
			$.ajax({
				url: ajaxurl,
				data: {action: 'kom_homepage_clear_cache'}
			}).done(function(data) {
				$('#message-cache').fadeIn();
			});
		});

		var where = new Array();
			where[0] = 'Bottom drawer, always the last place you look.';
			where[1] = 'Top drawer, got it!';
			where[2] = 'Middle drawer, found it!';
		var rando = Math.floor(Math.random() * where.length);

		$('.fetch-article-data-button').on('click', function() {

			var $this = $(this);
			var $root = $this.parentsUntil('.postbox');
			var position = $root.find('.savedposition').val();
			var content_id = $this.parent().find('.fetch-content-data').val();
			
			$this.parent().find('.fetch-content-status').removeClass('good bad').html('Searching through file cabinet...').show();

			if(content_id) {

				$.ajax({
					url: ajaxurl,
					dataType: 'json',
					data: {action: 'kom_homepage_fetch_post_data', id: content_id},
					success: function(data, textStatus) {
						if(data.text == 'noid') {
							$this.parent().find('.fetch-content-status').addClass('bad').html('That ID doesn\'t exist, sorry.');
						} else {

							$this.parent().find('.fetch-content-status').addClass('good').html(where[rando]);

							$root.find('#link\\['+ position +'\\]\\[featured-link-text\\]').val(data.text).effect('highlight', {}, 2000);
							$root.find('#link\\['+ position +'\\]\\[featured-link-url\\]').val(data.link).effect('highlight', {}, 2000);
							$root.find('#link\\['+ position +'\\]\\[featured-link-image\\]').val(data.image).effect('highlight', {}, 2000);							
						}
					},
					error: function(msg) {
						$this.parent().find('.fetch-content-status').addClass('bad').html('Error: ' + msg);
					}
				});

			} else {
				
				$this.parent().find('.fetch-content-status').addClass('bad').html('That box can\'t be blank you silly goose!');

			}
		});

		$('.fetch-video-data-button').on('click', function() {

			var $this = $(this);
			var $root = $this.parentsUntil('.postbox');
			var position = $root.find('.savedposition').val();
			var content_id = $this.parent().find('.fetch-content-data').val();
			
			$this.parent().find('.fetch-content-status').removeClass('good bad').html('Searching through file cabinet...').show();

			if(content_id) {

				$.ajax({
					url: '<?php echo VIDEOS_BASE_URI; ?>/watch/' + content_id + '.json',
					dataType: 'json',
					success: function(data, textStatus) {

						$this.parent().find('.fetch-content-status').addClass('good').html(where[rando]);

						$root.find('#link\\['+ position +'\\]\\[featured-link-text\\]').val(data.episode.episode_version.title).effect('highlight', {}, 2000);
						$root.find('#link\\['+ position +'\\]\\[featured-link-url\\]').val('<?php echo VIDEOS_BASE_URI; ?>/watch/' + content_id + '/' + data.episode.episode_version.url_title).effect('highlight', {}, 2000);
						$root.find('#link\\['+ position +'\\]\\[featured-link-image\\]').val(data.episode.episode_version.still_image_cdn_uri).effect('highlight', {}, 2000);							
					},
					error: function(msg) {
						$this.parent().find('.fetch-content-status').addClass('bad').html('Error: ' + msg);
					}
				});

			} else {
				
				$this.parent().find('.fetch-content-status').addClass('bad').html('That box can\'t be blank you silly goose!');

			}
		});
		
	});
	</script>

<?php }
}