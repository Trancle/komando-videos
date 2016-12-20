<?php
/**
 * @package kom-article-gallery
 */


class Kom_article_gallery_settings {

    /**
     * __construct function.
     */
    public function __construct(){
        // Actions
        add_action( 'admin_menu',array($this, 'add_theme_settings_page') );
        add_action( 'admin_notices', array($this, 'theme_settings_admin_notices') );
        add_action( 'admin_init',array($this, 'register_settings') );
    }

    /**
     * register_settings function.
     * @return void
     */
    public function register_settings(){
        $args = array(
            'id'			=> "general_settings",
            'title'			=> __("Settings", 'afb'),
            'page'			=> "kom_article_gallery_settings_page",
            'description'	=> __("These settings control some general aspects of the article galleries.", 'afb'),
        );
        $settings_example_section = new kom_article_gallery_settings_section($args);

        $args = array(
            'id'				=> 'kom_article_gallery_show_ads_after',
            'title'				=> __("Show Ads After", 'afb'),
            'page'				=> 'kom_article_gallery_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The number of images to show before interspersed ad is shown.", 'afb'),
            'type'				=> 'text', // text, textarea, password, checkbox
            'option_group'		=> "settings_page_kom_article_gallery_settings_page",
        );
        $kom_article_gallery_show_ads_after = new kom_article_gallery_settings_field($args);

        $args = array(
            'id'				=> 'kom_article_gallery_show_images_desktop',
            'title'				=> __("Max Thumbnails - Desktop", 'afb'),
            'page'				=> 'kom_article_gallery_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The number of image thumbnails to show at once on a desktop.", 'afb'),
            'type'				=> 'text', // text, textarea, password, checkbox
            'option_group'		=> "settings_page_kom_article_gallery_settings_page",
        );
        $kom_article_gallery_show_images_desktop = new kom_article_gallery_settings_field($args);

        $args = array(
            'id'				=> 'kom_article_gallery_show_images_mobile',
            'title'				=> __("Max Thumbnails - Mobile", 'afb'),
            'page'				=> 'kom_article_gallery_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The number of image thumbnails to show at once on a mobile device.", 'afb'),
            'type'				=> 'text', // text, textarea, password, checkbox
            'option_group'		=> "settings_page_kom_article_gallery_settings_page",
        );
        $kom_article_gallery_show_images_mobile = new kom_article_gallery_settings_field($args);

    }


    /**
     * Register the Theme Settings Page. add_theme_settings_page function.
     * @return void
     */
    public function add_theme_settings_page(){
        $theme_page = add_options_page( __("Kom Article Gallery", "afb"), __("Kom Article Gallery", "afb"), 'switch_themes', 'kom_article_gallery_settings_page', array($this, 'lh_settings_page') );

    }

    /**
     * lh_settings_page function.
     * @return void
     */
    public function lh_settings_page(){
        ?>
        <div class="wrap">
            <div class="icon32" id="icon-options-general"></div>
            <h2><?php _e("Kom Article Gallery", 'afb'); ?></h2>

            <form action="options.php" method="post">
                <?php
                settings_fields('settings_page_kom_article_gallery_settings_page');
                do_settings_sections('kom_article_gallery_settings_page');
                ?>
                <p class="submit">
                    <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes','gb'); ?>" />
                </p>

            </form>
        </div><!-- wrap -->
        <?php
    }

    /**
     * theme_settings_admin_notices function.
     * @return void
     */
    public function theme_settings_admin_notices(){
        if(isset($_GET['page']) && $_GET['page'] != "lh_theme_settings"){
            return;
        }

        if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == true){
            add_settings_error('kom_article_gallery_settings_page', 'kom_article_gallery_settings_page', __("Successfully updated.", 'afb') , 'updated');
        }

        settings_errors('kom_article_gallery_settings_page');

    }

}
$lh_theme_settings = new Kom_article_gallery_settings();


/**
 * lh_settings_section class.
 */
class kom_article_gallery_settings_section {

    private $args;

    /**
     * __construct function.
     * @param mixed $args
     */
    public function __construct( $args ){
        $defaults = array(
            'id'			=> NULL,
            'title'			=> NULL,
            'page'			=> NULL,
            'description'	=> NULL,
        );
        $args = wp_parse_args( $args, $defaults );

        $this->args = $args;

        $this->register_section();
    }

    /**
     * register_section function.
     * @param mixed $args
     * @return void
     */
    private function register_section(){
        add_settings_section(
            $this->args['id'],
            $this->args['title'],
            array($this, 'output_callback'),
            $this->args['page']
        );
    }

    /**
     * output_callback function.
     * @return void
     */
    public function output_callback(){
        ?>
        <p><?php echo $this->args['description'] ?></p>
        <?php
    }

}

/**
 * lh_settings_field class.
 */
class kom_article_gallery_settings_field {

    private $args;

    /**
     * __construct function.
     * @param mixed $args
     */
    public function __construct( $args ){
        $defaults = array(
            'id'				=> NULL,
            'title'				=> NULL,
            'page'				=> NULL,
            'section'			=> NULL,
            'description'		=> NULL,
            'type'				=> 'text', // text, textarea, password, checkbox
            'sanitize_callback'	=> NULL,
            'option_group'		=> NULL,
        );

        $this->args = wp_parse_args( $args, $defaults );

        $this->register_field();
    }

    /**
     * register_field function.
     * @return void
     */
    private function register_field(){
        add_settings_field(
            $this->args['id'],
            '<label for="'.$this->args['id'].'">'.$this->args['title'].'</label>',
            array($this, 'output_callback'),
            $this->args['page'],
            $this->args['section']
        );

        register_setting($this->args['option_group'], $this->args['id'], isset($this->args['sanatize_callback']) ? $this->args['sanatize_callback'] : NULL );
    }

    /**
     * output_callback function.
     * @return void
     */
    public function output_callback(){
        $t = $this->args['type'];
        if($t == "text"):
            ?>
            <fieldset>
                <input type="text" class="all-options" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>" value="<?=get_option($this->args['id'])?>">
                <p class="description">
                    <?php echo $this->args['description']; ?>
                </p>
            </fieldset>
            <?php
        elseif($t == "textarea"):
            ?>
            <fieldset>
                <textarea class="all-options" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>"><?=get_option($this->args['id'])?></textarea>
                <p class="description">
                    <?php echo $this->args['description']; ?>
                </p>
            </fieldset>
            <?php
        elseif($t == "password"):
            ?>
            <fieldset>
                <input type="password" class="all-options" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>" autocomplete="off" value="<?=get_option($this->args['id'])?>">
                <p class="description">
                    <?php echo $this->args['description']; ?>
                </p>
            </fieldset>
            <?php
        elseif($t == "checkbox"):
            ?>
            <fieldset>
                <label for="<?=$this->args['id']?>">
                    <input type="checkbox" class="" name="<?=$this->args['id']?>" id="<?=$this->args['id']?>" autocomplete="off" value="1" <?php checked(get_option($this->args['id'])); ?>>
                    <?php echo $this->args['description']; ?>
                </label>
            </fieldset>
            <?php
        elseif($t == "category"):
            ?>
            <fieldset>
                <?php
                $args = array(
                    "name"				=> $this->args['id'],
                    "id"				=> $this->args['id'],
                    "selected"			=> get_option($this->args['id']),
                    "show_option_none"	=> __("Not selected", 'afb'),
                );
                wp_dropdown_categories( $args ); ?>
                <p class="description">
                    <?php echo $this->args['description']; ?>
                </p>
            </fieldset>
            <?php
        elseif($t == "callback"):
            call_user_func($this->args['callback'], $this->args);
        endif;
    }

}