<?php
/**
 * Plugin Name: Video Embed Plugin For Youtube
 * Plugin URI: http://example.com
 * Description: Embed youtube video with some additional params.
 * Version: 1.0.0
 * Author: Mahabub Alam
 * Author URI: http://example.com
 * License: GPL2
 */

/* Add Stylesheet */

function embed_video_style()
{
    wp_enqueue_style('embed_video_style', plugin_dir_url(__FILE__) . '/css/embed_video.css');
}

add_action('admin_enqueue_scripts', 'embed_video_style');

/* Add Script */

add_action('admin_enqueue_scripts', 'embed_video_script');
function embed_video_script()
{
    wp_enqueue_script('embed_video', plugin_dir_url(__FILE__) . '/js/embed_video.js', array('jquery'), '1.0', true);
}


add_action('admin_menu', 'embed_video_menu');

function embed_video_menu()
{
    add_menu_page('Embed Video', 'Embed Video', 'administrator', 'embed_video_content', 'embed_video_page', 'dashicons-format-video');
}

function embed_video_page()
{
    ?>
    <div class="add-media-wrapper">
        <h2> Embed Your Video </h2>
        <div class="form-wrapper">
            <form method="post" action="">
                <table class="input-table">
                    <tr>
                        <td class="label"> Embed Name</td>
                        <td><input type="text" required="true" name="embed_video[name]"/></td>
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
                                        <input type="radio" value="youtube" name="embed_video[source]"> Youtube
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[source]" value="viemo">Viemo
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[source]" value="cnet"> Cnet
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[source]" value="cbs"> CBS
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[source]" value="html"> HTML
                                    </label>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"> Video ID
                            <p class="description"> Youtube Video ID </p>
                        </td>
                        <td><input type="text" name="embed_video[video_id]" required/></td>
                    </tr>
                    <tr>
                        <td class="label"> Auto Play?</td>
                        <td>
                            <ul class="acf-radio-list radio horizontal">
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[auto_play]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[auto_play]" value="0" checked="checked">No
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
                                        <input type="radio" name="embed_video[related_video]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[related_video]" value="0"
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
                                        <input type="radio" name="embed_video[video_info]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[video_info]" value="0" checked="checked">No
                                    </label>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"> Offset
                            <p class="description"> Start offset in seconds </p>
                        </td>
                        <td><input type="text" name="embed_video[offset]" required/></td>
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
                                        <input type="radio" name="embed_video[both_visible]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[both_visible]" value="0"
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
                                        <input type="radio" name="embed_video[auto_hide]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[auto_hide]" value="0" checked="checked">No
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
                                        <input type="radio" name="embed_video[auto_hide_progressbar]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[auto_hide_progressbar]" value="0"
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
                                        <input type="radio" name="embed_video[display_control]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[display_control]" value="0"
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
                                        <input type="radio" name="embed_video[display_control_caption]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[display_control_caption]" value="0"
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
                                        <input type="radio" name="embed_video[loop]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[loop]" value="0" checked="checked">No
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
                                        <input type="radio" name="embed_video[show_annotation]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[show_annotation]" value="0"
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
                                        <input type="radio" name="embed_video[modest_branding]" value="1"> Yes
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="embed_video[modest_branding]" value="0"
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

?>
