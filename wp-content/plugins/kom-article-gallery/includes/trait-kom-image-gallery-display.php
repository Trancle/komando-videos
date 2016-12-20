<?php
/**
 * Contains functions to display an image gallery in an article.
 * Author: Tyger Gilbert
 * Date: 4/22/2016
 * Time: 2:16 PM
 */

trait Kom_Image_Gallery_Display
{
    public function post_has_gallery($post_id = '' ){
        // See if a gallery is specified for this article.
        // Returns TRUE if there is, FALSE if not.
        // Used in single.php and in next function below.

        // No Article ID No. was passed.
        if( empty($post_id) ) return false;

        $custom_fields = get_post_custom($post_id);

        return !is_null($custom_fields['gallery_images']) && //Not null
        !empty($custom_fields['gallery_images']) && // Not empty
        isset($custom_fields['gallery_images'][0]) && // has 0th element
        "[]" != $custom_fields['gallery_images'][0] && // 0th element not empty brackets
        1 < count(json_decode($custom_fields['gallery_images'][0], true)); //there are at least 2 images in the gallery
    }

    public function clean_up_json($json){
        $json = str_replace("'", "&#8217;", $json);
        $json = str_replace("\\n", "<br \/>", $json);
        $json = json_decode($json);

        foreach($json as $key => $image){
            $json[$key]->image_url = k2_get_static_image_url($image->image_url);
        }

        $json = json_encode($json, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_QUOT|JSON_HEX_APOS);
        //\u0022 = "
        //\u0027 = '
        $json = str_replace('\u0022', '\\\"', $json); //this is necessary because of strange JSON conversion issues
        return $json;
    }

    public function display_article_image_gallery_html( $post_id = '' ){
        // Creates html to display the gallery and Returns it.
        // Used in single.php where an image gallery would be inserted.

        if (empty($post_id) OR !self::post_has_gallery($post_id)){
            // No Article ID No. was passed.
            return FALSE;
        }
        // Get the gallery information

        $custom_fields = get_post_custom($post_id); // Array of all custom fields
        $gallery_images = $custom_fields['gallery_images'][0]; // Serialized string of gallery images
        // JSON decode the array
        $gallery_image_list = json_decode($gallery_images, true); // Array of gallery image arrays
        $total_images = count($gallery_image_list);
        $main_image = (array)$gallery_image_list[0];
        $gallery_images_to_js = self::clean_up_json($gallery_images);//$custom_fields['gallery_images'][0];//json_encode($gallery_image_list, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);


        $show_images_desktop = get_option("kom_article_gallery_show_images_desktop");
        $show_images_mobile = get_option("kom_article_gallery_show_images_mobile");
        $show_ads_after = get_option("kom_article_gallery_show_ads_after");

        // Compose the HTML for the gallery
        $gallery = '<div class="image-gallery-outer-wrapper">
            <script type="text/javascript">
                var komando_gallery_images_img_data_json = \'' . $gallery_images_to_js . '\';
                var komando_gallery_images_image_data = JSON.parse(komando_gallery_images_img_data_json);               
                var komando_gallery_images_total_images = ' . $total_images . ';
                var komando_gallery_images_show_images_desktop = ' . ($show_images_desktop > 0 ? $show_images_desktop : 8) . ';
                var komando_gallery_images_show_images_mobile = ' . ($show_images_mobile > 0 ? $show_images_mobile : 4) . ';
                var komando_gallery_images_show_ads_after = ' . ($show_ads_after > 0 ? $show_ads_after : 3) . ';
                 //image_data.push();
            </script>
            <div class="ad gallery-leaderboard clearfix">
                <div id="ad-leaderboard-gallery">
                    <!-- /1064811/k2-www-gallery-leaderboard -->
                    <div id="k2-www-gallery-leaderboard">
                        <script type="text/javascript">
                        googletag.cmd.push(function() { googletag.display("k2-www-gallery-leaderboard"); });
                        </script>
                    </div>
                </div>
            </div>
            <div class="image-gallery-inner-wrapper">
                <div class="image-display">
                    <!-- Main image is shown as the background of this <div> -->
                    <div class="image-frame">
                        <div class="inner-image-frame">&nbsp;</div>
                        <!-- Previous image link -->
                        <div class="prev-image gallery-part" id="prev"><i class="imageNav prev fa fa-chevron-left"></i></div>
                        <!-- Next image link -->
                        <div class="next-image gallery-part" id="next"><i class="imageNav next fa fa-chevron-right"></i></div>
                    <div class="ad ad-frame">
                            <div class="interspersed-ad-close-button-x">X</div>
                        <div class="ad ad-interspersed">
                            <!-- /1064811/k2-www-gallery-interspersed -->
                            <div id=\'k2-www-gallery-interspersed\'>
                            <script type=\'text/javascript\'>
                            googletag.cmd.push(function() { googletag.display(\'k2-www-gallery-interspersed\'); });
                            </script>
                            </div>
                            <div class="interspersed-ad-close-button">Close</div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="image-identity gallery-part">
                    <div class="image-num">1 of ' . $total_images . '</div>
                    ' . (!empty($main_image["photo_credit"]) ? '<div class="photo-credit">Photo Credit: <a href="' . $main_image["credit_link"] . '">' . $main_image["photo_credit"] . '</a></div>' : "") . '
                </div>
                <div class="image-index-bar gallery-part">
                    <!-- Long image bar is shown only if screen is 560px wide or more. It has 8 index images. -->
                    <div class="long-image-bar"></div>
                    <!-- Short image bar is shown only if screen width is less than 560px. It has 4 index images. -->
                </div>
                <div class="image-info gallery-part">
                    <h2 class="image-title">' . $main_image['title'] . '</h2>
                    <div class="image-text">' . $main_image['caption_text'] . '</div>
                </div>
            </div>
            <div class="ad gallery-main-ad">
                <div id="ad-half-page-gallery">
                    <!-- /1064811/k2-www-gallery-right -->
                    <div id="k2-www-gallery-right">
                    <script type="text/javascript">
                    googletag.cmd.push(function() { googletag.display("k2-www-gallery-right"); });
                    </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="extra-padding">&nbsp;</div>
        ';

        return $gallery;
    }

}
