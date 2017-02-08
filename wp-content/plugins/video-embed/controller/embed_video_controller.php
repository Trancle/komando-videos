<?php
require_once plugin_dir_path(plugin_dir_path(__FILE__)) . 'models/embed_video.php';

class EmbedVideoController
{
    public $objEmbedVideo;

    function __construct()
    {
        $this->objEmbedVideo = new VideoEmbed();
    }

    function create()
    {
        $status = $this->objEmbedVideo->create($_POST);
        if (!$status) {
            $this->new_embed_video($this->objEmbedVideo);
        }

        else {
            $this->show();
        }
    }

    function show()
    {
       ?>
        <div class="add-media-wrapper">
            <!--            <iframe src="https://www.youtube.com/embed/x4AaYr1t3TA" width="500" height="200"></iframe>-->
            <h2> Embed Your Video </h2>
            <div class="form-wrapper">
                    <table class="input-table">
                        <tr>
                            <td class="label"> Embed Name</td>
                            <td><span><?php echo $this->objEmbedVideo->name;  ?></span></td>
                        </tr>
                        <tr>
                            <td class="label">
                                Source*
                            </td>
                            <td><span><?php echo $this->objEmbedVideo->source;  ?></span></td>
                        </tr>
                        <tr>
                            <td class="label"> Video ID
                                <p class="description"> Youtube Video ID </p>
                            </td>
                            <td><span><?php echo $this->objEmbedVideo->video_id;  ?></span></td>
                        </tr>

                        <tr>
                            <td class="label">
                                <p class="description"> Embed Video </p>
                            </td>
                            <td><span><?php echo $this->objEmbedVideo->embed_url;  ?></span>&nbsp &nbsp &nbsp
                                <iframe width="300" height="100"
                                        src="<?php echo $this->objEmbedVideo->embed_url ;?>">
                                </iframe>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">
                                <p class="description">Still Image </p>
                            </td>
                            <td><span><?php echo $this->objEmbedVideo->still_image;  ?></span>&nbsp &nbsp &nbsp
                                <img src="<?php echo $this->objEmbedVideo->still_image ?>"  height="100" width="100">
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Auto Play?</td>
                            <td>
                                <?php if($this->objEmbedVideo->auto_play)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Show Related Video?</td>
                            <td>
                                <?php if($this->objEmbedVideo->related_video)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Show Video Information?</td>
                            <td>
                                <?php if($this->objEmbedVideo->video_info)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Offset
                                <p class="description"> Start offset in seconds </p>
                            </td>
                            <td><span><?php echo $this->objEmbedVideo->offset ?></span></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h2> Auto Hide Progress Bar and Player Controls </h2></td>
                        </tr>

                        <tr>
                            <td class="label"> Both always Visible?</td>
                            <td>
                                <?php if($this->objEmbedVideo->both_visible)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Auto-hide both?</td>
                            <td>
                                <?php if($this->objEmbedVideo->auto_hide)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Auto Hide Progress Bar?</td>
                            <td>
                                <?php if($this->objEmbedVideo->auto_hide_progressbar)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Display Controls?</td>
                            <td>
                                <?php if($this->objEmbedVideo->display_control)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Display Control Caption?</td>
                            <td>
                                <?php if($this->objEmbedVideo->display_control_caption)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Loop?</td>
                            <td>
                                <?php if($this->objEmbedVideo->loop_video)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Show Video Annotations?</td>
                            <td>
                                <?php if($this->objEmbedVideo->show_annotation)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Modest Branding?
                                <p class="description"> hide youtube logo </p>
                            </td>
                            <td>
                                <?php if($this->objEmbedVideo->modest_branding)
                                {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                        </tr>

                    </table>
            </div>
        </div>
        <?php
    }

    function edit()
    {

    }

    function index()
    {

    }

    function new_embed_video($objEmbed)
    {
        ?>
        <div class="add-media-wrapper">
            <!--            <iframe src="https://www.youtube.com/embed/x4AaYr1t3TA" width="500" height="200"></iframe>-->
            <h2> Embed Your Video </h2>
            <div class="form-wrapper">
                <form method="post" name="embed_video" action="">
                    <table class="input-table">
                        <tr>
                            <td class="label"> Embed Name</td>
                            <td><input type="text" value="<?php echo $objEmbed->name ?>" required="true" name="name"/></td>
                        </tr>
                        <tr>
                            <td class="label">
                                Source*
                                <p class="description"> Select Select your video format </p>
                            </td>
                            <td>
                                <ul class="source-list">
                                    <li>
                                        <label>
                                            <input type="radio" value="youtube" name="source"> Youtube
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="source" value="viemo">Viemo
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="source" value="cnet"> Cnet
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="source" value="cbs"> CBS
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="source" value="html"> HTML
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Video ID
                                <p class="description"> Youtube Video ID </p>
                            </td>
                            <td><input type="text" value="<?php echo $objEmbed->video_id ?>" name="video_id" required/></td>
                        </tr>
                        <tr>
                            <td class="label"> Auto Play?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="auto_play" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="auto_play" value="0" checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Show Related Video?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="related_video" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="related_video" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Show Video Information?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="video_info" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="video_info" value="0" checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"> Offset
                                <p class="description"> Start offset in seconds </p>
                            </td>
                            <td><input type="text" value="<?php echo $objEmbed->offset ?>" name="offset" required/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><h2> Auto Hide Progress Bar and Player Controls </h2></td>
                        </tr>

                        <tr>
                            <td class="label"> Both always Visible?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="both_visible" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="both_visible" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Auto-hide both?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="auto_hide" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="auto_hide" value="0" checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Auto Hide Progress Bar?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="auto_hide_progressbar" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="auto_hide_progressbar" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Display Controls?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="display_control" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="display_control" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Display Control Caption?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="display_control_caption" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="display_control_caption" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Loop?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="loop_video" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="loop" value="0" checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Show Video Annotations?</td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="show_annotation" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="show_annotation" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <tr>
                            <td class="label"> Modest Branding?
                                <p class="description"> hide youtube logo </p>
                            </td>
                            <td>
                                <ul class="acf-radio-list radio horizontal">
                                    <li>
                                        <label>
                                            <input type="radio" name="modest_branding" value="1"> Yes
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" name="modest_branding" value="0"
                                                   checked="checked">No
                                        </label>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                    </table>
                    <div class="actions-wrapper">
                        <button type="submit" class="btn-submit" value="Embed Video" name="embed_video"> Embed Video
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    function handle_request()
    {
        if (isset($_POST['embed_video'])) {
            $this->create();
        } else {
            $this->new_embed_video($this->objEmbedVideo);
        }
    }
}