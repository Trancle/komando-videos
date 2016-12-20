<?php
/**
 *
 */

class Kom_Presented_By
{
    private function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_presented_by_meta_box')); // Adds the meta box on the editor
        add_action('save_post', array($this, 'presented_by_meta_save_details')); // Saving the meta box data

        // Prevents the post from losing its feature status when going from scheduled to published
        add_action('future_to_publish', array($this, 'publishing_post'));

        add_image_size('presented_by_image', 130, 65, false);
        add_filter( 'image_size_names_choose', array($this, 'add_image_name') );
    }

    public function add_image_name($sizes)
    {
        return array_merge( $sizes, array(
            'presented_by_image' => __('Presented By Image'),
        ) );
    }

    public function publishing_post($post)
    {
        remove_action('save_post', array($this, 'presented_by_meta_save_details'));
    }

    /**
     * Initialization
     *
     * @return string
     */
    public static function init()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new Kom_Presented_By();
        }

        return $instance;
    }

    /**
     * Create the meta box in the editor
     */
    public function add_presented_by_meta_box()
    {
        if (current_user_can('edit_posts')) {

            // Editor's Picks checkbox container
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'post', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'columns', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'downloads', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'apps', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'cool_sites', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'tips', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'buying_guides', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'happening_now', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'small_business', 'normal', 'default');
            add_meta_box('presented_by_meta_id', '"Presented By" Advertising', array($this, 'presented_by_meta_box'), 'new_technologies', 'normal', 'default');
        }
    }

    /**
     * Meta box HTML
     */
    public function presented_by_meta_box()
    {
        global $post;
        $presented_by_active = get_post_meta($post->ID, 'presented_by_active', true);
        $presented_by_advertiser = get_post_meta($post->ID, 'presented_by_advertiser', true);
        $presented_by_logo_file = get_post_meta($post->ID, 'presented_by_logo_file', true);
        $presented_by_text = get_post_meta($post->ID, 'presented_by_text', true);
        $presented_by_slogan = get_post_meta($post->ID, 'presented_by_slogan', true);
        $presented_by_link = get_post_meta($post->ID, 'presented_by_link', true);
        $presented_by_tracking = get_post_meta($post->ID, 'presented_by_tracking', true);
        $cActiveChk = '';
        if ($presented_by_active > 0) {
            $cActiveChk = ' checked';
        }
        ?>

        <div>
            <input type="checkbox" id="presented_by_active" name="presented_by_active"<?php echo $cActiveChk; ?>>
            <label for="presented_by_active"> "Presented By" Ad is active.</label>
            <table width="100%" border="0" cellspacing="0" cellpadding="4" style="margin: 10px 0;">
                <tr>
                    <td width="50%"><label for="sponsor-image">Advertiser</label><br>
                        <input type="text" style="width:100%" name="presented_by_advertiser" value="<?php echo $presented_by_advertiser; ?>"></td>
                    <td width="50%">
                        <label for="upload_image_button button">Logo File <em>(130 px x 65 px max.)</em></label><br>
                        <input class="sponsor-image" id="presented_by_logo_file" type="text" style="width:50%" name="presented_by_logo_file" value="<?php echo $presented_by_logo_file; ?>" />
                        <input class="upload_image_button button" type="button" value="Upload Image" /></td>
                </tr>
                <tr>
                    <td width="100%" colspan="2"><label for="presented_by_text">"Presented By" Text</label><br>
                        <input type="text" style="width:100%" name="presented_by_text" value="<?php echo $presented_by_text; ?>"></td>
                </tr>
                <tr>
                    <td width="100%" colspan="2"><label for="presented_by_slogan">Advertiser Slogan Line</label><br>
                        <input type="text" style="width:100%" name="presented_by_slogan" value="<?php echo $presented_by_slogan; ?>"></td>
                </tr>
                <tr>
                    <td width="50%"><label for="presented_by_link">Link URL to Advertiser</label><br>
                        <input type="text" style="width:100%" name="presented_by_link" value="<?php echo $presented_by_link; ?>"></td>
                    <td width="50%"><label for="presented_by_tracking">Advertiser's Tracking Code</label><br>
                        <input type="text" style="width:100%" name="presented_by_tracking" value="<?php echo $presented_by_tracking; ?>"></td>
                </tr>
            </table>
        </div>
        <script>
            jQuery(function($){
                var custom_uploader;
                $('.upload_image_button').on('click', function(e) {
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
                        $(active_button).parent().find('.sponsor-image').val(attachment.sizes.presented_by_image.url);
                    });

                    // Open the uploader dialog
                    custom_uploader.open();
                });
            })
        </script>
    <?php
    }

    /**
     * Save the meta box data
     */
    public function presented_by_meta_save_details($post_id)
    {
        global $post;

        // to prevent metadata or custom fields from disappearing...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Determine if Presented By is Active box is checked and assign 1 if yes, or 0 if no.
        if (isset($_POST['presented_by_active'])) {
            $is_active = 1;
        } else {
            $is_active = 0;
        }

        // Save the data to the post.
        update_post_meta($post->ID, 'presented_by_active', $is_active);
        update_post_meta($post->ID, 'presented_by_advertiser', stripslashes(htmlspecialchars(strip_tags($_POST['presented_by_advertiser']))));
        update_post_meta($post->ID, 'presented_by_logo_file', stripslashes(htmlspecialchars(strip_tags($_POST['presented_by_logo_file']))));
        update_post_meta($post->ID, 'presented_by_text', stripslashes(htmlspecialchars(strip_tags($_POST['presented_by_text']))));
        update_post_meta($post->ID, 'presented_by_slogan', stripslashes(htmlspecialchars(strip_tags($_POST['presented_by_slogan']))));
        update_post_meta($post->ID, 'presented_by_link', stripslashes(htmlspecialchars(strip_tags($_POST['presented_by_link']))));
        update_post_meta($post->ID, 'presented_by_tracking', stripslashes($_POST['presented_by_tracking']));

    }

    /**
     * Checks if the Presented By Ad is active or not
     *
     * @param number $post_id
     * @return bool
     */
    public static function presented_by_is_active($post_id)
    {
        $is_active = get_post_meta($post_id, 'presented_by_active', true);

        // Return true if is_active is 1, else false
        if ($is_active > 0){
            return true;
        }
        return false;
    }

    /**
     * Creates HTML code to display Presented By Ad
     * @param $post_id
     */
    public static function show_presented_by_ad($post_id)
    {
        $presented_by_advertiser = get_post_meta($post_id, 'presented_by_advertiser', true);
        $presented_by_logo_file = get_post_meta($post_id, 'presented_by_logo_file', true);
        $presented_by_text = get_post_meta($post_id, 'presented_by_text', true);
        $presented_by_slogan = get_post_meta($post_id, 'presented_by_slogan', true);
        $presented_by_link = get_post_meta($post_id, 'presented_by_link', true);
        $presented_by_tracking = get_post_meta($post_id, 'presented_by_tracking', true);

        $cHTML = '<a href="' . $presented_by_link . '" class="article-presented-by clearfix" target="_blank"><img src="' . $presented_by_logo_file . '" class="article-presented-by__img" alt="' . $presented_by_advertiser . '">';
        $cHTML .= '<div class="article-presented-by__text">Presented by ' . $presented_by_text . '</div>';
        $cHTML .= '<div class="article-presented-by__slogan">' . $presented_by_slogan . '</div>';
        $cHTML .= '<div class="article-presented-by__tracking">' . $presented_by_tracking . '</div></a>';

        return $cHTML;
    }
}



















