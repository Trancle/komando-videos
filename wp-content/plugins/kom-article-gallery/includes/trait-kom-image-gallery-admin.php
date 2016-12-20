<?php
/**
 * Author: Tyger Gilbert
 * Date: 4/27/2016
 * Time: 11:30 AM
 *
 * Provides entry fields and functions for creating an image
 * gallery in the article edit page of the administrative back
 * room. Each gallery applies to only one article and the images
 * for each gallery are entered and selected using the usual
 * Wordpress media procedures.
 */

/**
 * Meta box HTML
 */
trait Kom_Image_Gallery_Admin
{
    public function image_gallery_meta_box()
    {
        global $post;

        // Convert the json encoded string into a multi-dimensional array
        $gallery_images = json_decode(get_post_meta($post->ID, 'gallery_images', true));

        // Check if there are already images in this gallery, and create an empty image if not
        if ((!is_array($gallery_images)) OR (count($gallery_images) < 1)) {
            $gallery_images = array();
        }

        //JSON encode the gallery images for AngularJS
        $gallery_images_json = json_encode($gallery_images);

        ?>
        <script>

            angular.module("articleResponsiveGallery", ['ui.tinymce']).controller("articleResponsiveGalleryCtrl", function($scope) {

                $scope.tinymceOptions = {
                    selector:'.caption-text',
                    plugins: "code,charmap,colorpicker,hr,lists,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wplink,wpdialogs,wptextpattern,wpview",
                    menu: {
                        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
                        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'}
                    },
                    toolbar: 'undo redo | styleselect | bold italic | link unlink | code'
                };

                //the wordpress media selection frame
                $scope.frame = wp.media({
                    title: 'Select or Upload an Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });

                //the images object stores all of the image data for the gallery
                $scope.images = <?=$gallery_images_json?>;

                //add an image to the images object
                $scope.addImage = function (image_id){
                    next_sort_priority = $scope.images.length;
                    $scope.images.push(
                        {"image_id":(Math.floor(Date.now()/1000)),"title":"","caption_text":"","photo_credit":"","credit_link":"","image_url":"","image_sort_priority":next_sort_priority}
                    );
                };

                //delete an image from the images object
                $scope.deleteImage = function(image_id,index_num){
                    $actually_delete = confirm("Do you really want to delete image " + image_id + "?");
//                    alert(id+"::"+index_num);
                    if($actually_delete){
                        if($scope.images[index_num].image_id == image_id){
                            $scope.images.splice(index_num,1);
                            for(var i = index_num; i < $scope.images.length; i++){
                                $scope.images[i].image_sort_priority--;
                            }
                        }
                    }
                };

                //change the sorting priority of an image in the images object
                $scope.moveLocation = function(index_num, direction){
                    current_item = $scope.images[index_num];
                    current_sort_priority = current_item.image_sort_priority;

                    if(direction == "up"){
                        $scope.images[index_num] = $scope.images[index_num - 1];
                        $scope.images[index_num - 1] = current_item;
                        $scope.images[index_num - 1].image_sort_priority = $scope.images[index_num].image_sort_priority;
                        $scope.images[index_num].image_sort_priority = current_sort_priority;

                    }
                    else if(direction == "down"){
                        $scope.images[index_num] = $scope.images[index_num + 1];
                        $scope.images[index_num + 1] = current_item;
                        $scope.images[index_num + 1].image_sort_priority = $scope.images[index_num].image_sort_priority;
                        $scope.images[index_num].image_sort_priority = current_sort_priority;
                    }
                };

                $scope.updateImageUrl = function (index_num, url){
                    $scope.images[index_num].image_url = url;//.replace("localhost","www");
                };

                $scope.getWPImageUrl = function(index_num){
                    $scope.current_upload_index_num = index_num;
                    // When an image is selected in the media frame...
                    $scope.frame.on( 'select', function() {
                        // Get media attachment details from the frame state
                        var attachment = $scope.frame.state().get('selection').first().toJSON();

                        // Send the attachment URL to our custom image input field.
                        $scope.updateImageUrl($scope.current_upload_index_num, attachment.url);
                        $scope.$apply();
                        $scope.current_upload_index_num = null;

                    });

                    // Finally, open the modal on click
                    $scope.frame.open();
                };
            });
            angular.element(document).ready(function() {
                angular.bootstrap(document.getElementById("articleResponsiveGallery"), ['articleResponsiveGallery']);
            });
        </script>

        <style>
            .gallery-tinymce-editor {
                border: 1px solid lightgrey;
            }
            .gallery-image-table {
                border:2px solid lightgrey;
                padding: 20px;
            }
        </style>
        <div id="articleResponsiveGallery" ng-controller="articleResponsiveGalleryCtrl">
            <ul>
                <li ng-repeat="image in images">
                    <table class="gallery-image-table" width="100%" border="0" cellspacing="0" cellpadding="2" style="margin: 5px 0;">
                        <tr>
                            <td width="20%" rowspan="3">
                                    <table ng-show="image.image_url == ''">
                                        <tr>
                                            <td style="width: 120px; height: 90px; text-align: center">No Image Yet</td>
                                        </tr>
                                    </table>
                                    <img ng-show="image.image_url !== ''" ng-src="{{image.image_url}}" width="120" height="90" />

                                <br>Image ID: <input type="text" name="image_id_{{image.image_id}}" ng-model="image.image_id" />
                                <br>Sort Priority: {{image.image_sort_priority}} <input type="button" ng-hide="$first" ng-click="moveLocation($index, 'up')" value="&lt;"><input type="button" ng-hide="$last" ng-click="moveLocation($index, 'down')" value="&gt;"><input type="hidden" ng-model="image.image_sort_priority" />

                                <p><input class="delete-image-button button" type="button" name="delete_{{image.image_id}}" ng-click="deleteImage(image.image_id,$index)" value="Delete"/></p>
                            </td>
                            <td width="40%"><label for="title_{{image.image_id}}">Title</label><br>
                                <input type="text" style="width:100%" name="title_{{image.image_id}}" ng-model="image.title" /></td>
                            <td width="40%">
                                <label for="image_url_{{image.image_id}}">Image File <em>(Up to 1000
                                        px x 750 px best.)</em></label><br>
                                <input id="image_url_{{image.image_id}}" type="text" style="width:40%" ng-model="image.image_url" name="image_url_{{image.image_id}}" />
                                <input class="DISupload_image_button button" type="button" ng-click="getWPImageUrl($index)" value="Select" /></td>
                        </tr>
                        <tr>
                            <td width="80%" colspan="2"><label for="caption_text_{{image.image_id}}">Caption Text</label><br>
                                <div class="gallery-tinymce-editor"><textarea class="caption-text" ui-tinymce="tinymceOptions" style="width:80%" rows="3" name="caption_text_{{image.image_id}}" ng-model="image.caption_text"></textarea></div>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%"><label for="photo_credit_{{image.image_id}}">Photo Credit</label><br>
                                <input type="text" style="width:40%" ng-model="image.photo_credit" name="photo_credit_{{image.image_id}}" /></td>
                            <td width="40%"><label for="credit_link_{{image.image_id}}">Credit Link</label><br>
                                <input type="text" style="width:40%" ng-model="image.credit_link" name="credit_link_{{image.image_id}}" /></td>
                        </tr>
                    </table>
                </li>
            </ul>
            <input type="hidden" name="total_images" ng-model="total_images" value="{{images.length}}" />
            <input type="hidden" name="images_json" value="{{images | json}}" />
            <div>
                <input class="add-new-image-button button" ng-click="addImage()" type="button" value="Add New Image" />
            </div>
        </div>


        <?php
    }

    public function image_gallery_pre_filter_caption_text($text){
        $text = preg_replace("/<a href=[\"'](.+?)\[\"'].*?>(.+?)<\/a>/", "[link url~$1~ text~$2~]", $text);
        return $text;
    }

    public function image_gallery_post_filter_caption_text($text){
        $text = preg_replace("/\[link url\~(.+?)\~ text\~(.+?)\~\]/", "<a href='$1'>$2</a>", $text);
        return $text;
    }

/**
     * Save the meta box data
     */
    public function image_gallery_meta_save_details($post_id)
    {
        global $post;

        // to prevent metadata or custom fields from disappearing...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if(!isset($_POST['images_json']) || empty($_POST['images_json'])){
            return false;
        }

        $gallery_images = $_POST['images_json'];

        update_post_meta($post->ID, 'gallery_images', $gallery_images);

    }
}