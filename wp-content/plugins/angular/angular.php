<?php
/*
Plugin Name: AngularJS Include
Plugin URI: http://www.komando.com
Description: Embeds AngularJS into Wordpress
Version: 0.2
Author: Komando
Author URI: http://www.komando.com
*/

add_action( 'admin_enqueue_scripts', 'angularScripts' );

function angularScripts()
{
  global $angular_is_loaded;
  global $angular_nvd3_is_loaded;
  if(!isset($angular_is_loaded) || !$angular_is_loaded){
    $angular_is_loaded = true;

    // Angular Core
    wp_enqueue_script('angular-core',       k2_get_static_url('v2').'/js/angular.min.js',          ['jquery'], null, false);
    wp_enqueue_script('angular-sanitize',   k2_get_static_url('v2').'/js/angular-sanitize.min.js', ['angular-core'], null, false);
    wp_enqueue_script('angular-cookies',    k2_get_static_url('v2').'/js/angular-cookies.min.js',  ['angular-core'], null, false);

    if(isset($angular_nvd3_is_loaded) || $angular_nvd3_is_loaded) {
      wp_enqueue_script('d3.min',       k2_get_static_url('v2') . '/js/d3.min.js', ['angular-core'], null, false);
      wp_enqueue_script('angular-nvd3', k2_get_static_url('v2') . '/js/angular-nvd3.js', ['angular-core'], null, false);
      wp_enqueue_script('nv.d3',        k2_get_static_url('v2') . '/js/nv.d3.js', ['angular-core'], null, false);

      wp_register_style( 'nv.d3',      k2_get_static_url('v2') . '/css/nv.d3.css', false,'1.1','all' );
      wp_enqueue_style( 'nv.d3' );
    }

    wp_enqueue_script('angular-app',        k2_get_static_url('v2').'/js/angular-app.js',          ['angular-core'], null, false); 
    // Angular Factories
    wp_enqueue_script('angular-factories',  k2_get_static_url('v2').'/js/angular-factories.js',    ['angular-app'], null, false);
    // Angular Directives
    wp_enqueue_script('angular-directives', k2_get_static_url('v2').'/js/angular-directives.js',   ['angular-factories'], null, false);
  }
}

function angular_to_php_json_decode($string, $array = false){
  $result = str_replace("\n", "", $string);
  $result = str_replace("\r", "", $result);
  $result = stripslashes($result);
  $result = json_decode($result, $array);
  return $result;
}

function php_to_angular_json_encode($string){
  $string = str_replace("'", "&rsquo;", str_replace('"', "&rdquo;", $string));
  $json = json_encode($string, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_QUOT|JSON_HEX_APOS);
  //$json = str_replace('\u0022', '\\\"', $json); //this is necessary because of strange JSON conversion issues
  $json = str_replace('\n', "", $json);
  $json = str_replace('\r', "", $json);
  return $json;
}

