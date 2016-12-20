<?php
/**
 * @package k2ia
 */


class K2_instant_articles_settings {

    /**
     * __construct function.
     */
    public function __construct(){
        // Actions
        add_action( 'admin_menu'    ,array($this, 'add_theme_settings_page') );
        add_action( 'admin_notices' ,array($this, 'theme_settings_admin_notices') );
        add_action( 'admin_init'    ,array($this, 'register_settings') );
    }

    /**
     * register_settings function.
     * @return void
     */
    public function register_settings(){
        $args = array(
            'id'			=> "general_settings",
            'title'			=> __("Settings", 'afb'),
            'page'			=> "k2ia_settings_page",
            'description'	=> __("These settings control some general aspects of the instant article plugin and how your instant articles are shown on facebook.", 'afb'),
        );
        $settings_example_section = new k2ia_settings_section($args);

        $args = array(
            'id'				=> 'k2ia_feed_key',
            'title'				=> __("Feed Key", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The Feed key name", 'afb'),
            'type'				=> 'text',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_feed_key = new k2ia_settings_field($args);

        $args = array(
            'id'				=> 'k2ia_ads',
            'title'				=> __("Url ads", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The Url Ads example: [{
                                                                \"name\": \"fb - instant - article - leaderboard\",
                                                                \"width\": \"320\",
                                                                \"height\": \"50\"
                                                            }, {
                                                                \"name\": \"fb - instant - article - content - 1\",
                                                                \"width\": \"300\",
                                                                \"height\": \"250\"
                                                            }, {
                                                                \"name\": \"name': 'fb - instant - article - content - 2\",
                                                                \"width\": \"300\",
                                                                \"height\": \"250\"
                                                            }]", 'afb'),
            'type'				=> 'textarea',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_ads = new k2ia_settings_field($args);

        $args = array(
            'id'				=> 'k2ia_like_media',
            'title'				=> __("Like Media", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("Allow users to like the media you embedded.", 'afb'),
            'type'				=> 'checkbox',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_like_media = new k2ia_settings_field($args);

        $args = array(
            'id'				=> 'k2ia_comment_media',
            'title'				=> __("Comment Media", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("Allow users to comment on the media you embedded.", 'afb'),
            'type'				=> 'checkbox',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_comment_media = new k2ia_settings_field($args);

        $args = array(
            'id'				=> 'k2ia_articles_num',
            'title'				=> __("Number of Articles", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The number of articles, that will be rendered on the feed.", 'afb'),
            'type'				=> 'text',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_comment_media = new k2ia_settings_field($args);

        $args = array(
            'id'				=> 'k2ia_paragraph_per_ads',
            'title'				=> __("Number of Articles", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The number of paragraphs between each ads.", 'afb'),
            'type'				=> 'text',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_comment_media = new k2ia_settings_field($args);

        $args = array(
            'id'				=> 'k2ia_copyright',
            'title'				=> __("Copyright", 'afb'),
            'page'				=> 'k2ia_settings_page',
            'section'			=> 'general_settings',
            'description'		=> __("The standard copyright details for your instant articles.", 'afb'),
            'type'				=> 'textarea',
            'option_group'		=> "settings_page_k2ia_settings_page",
        );
        $k2ia_copyright = new k2ia_settings_field($args);
    }


    /**
     * Register the Theme Settings Page. add_theme_settings_page function.
     * @return void
     */
    public function add_theme_settings_page(){
        $theme_page = add_options_page( __("K2 Instant Articles", "afb"), __("K2 Instant Articles", "afb"), 'switch_themes', 'k2ia_settings_page', array($this, 'lh_settings_page') );

    }

    /**
     * lh_settings_page function.
     * @return void
     */
    public function lh_settings_page(){
        ?>
        <div class="wrap">
            <div class="icon32" id="icon-options-general"></div>
            <h2><?php _e("K2 Instant Articles", 'afb'); ?> <a href="<?php echo add_query_arg(array("feed" => "instant_articles"), trailingslashit(get_home_url())); ?>" class="button-primary" target="_blank"> <?php _e("Show feed", "afb"); ?> </a></h2>

            <form action="options.php" method="post">
                <?php
                settings_fields('settings_page_k2ia_settings_page');
                do_settings_sections('k2ia_settings_page');
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
            add_settings_error('k2ia_settings_page', 'k2ia_settings_page', __("Successfully updated.", 'afb') , 'updated');
        }

        settings_errors('k2ia_settings_page');

    }

}
$lh_theme_settings = new K2_instant_articles_settings();


/**
 * lh_settings_section class.
 */
class k2ia_settings_section {

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
class k2ia_settings_field {

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
            'type'				=> 'text',
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