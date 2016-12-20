<?php
/*
Plugin Name: AngularJS TinyMCE for Wordpress Admin
Plugin URI: http://www.komando.com
Description: Includes TinyMCE for Angular in the Wordpress Admin
Version: 1.0
Author: Komando
Author URI: http://www.komando.com
*/

add_action('admin_head', 'angular_tinymce_add_javascript' );

function angular_tinymce_add_javascript() {
  global $angular_is_loaded;
  if(!isset($angular_is_loaded) || !$angular_is_loaded){
    angular_add_javascript();
  }
  echo '<script src="' . k2_get_static_url('v2') . '/js/tinymce/tinymce-angular/tinymce-angular.min.js" type="text/javascript"></script>' . "\n";
}

?>