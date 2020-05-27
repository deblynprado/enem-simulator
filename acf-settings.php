<?php

$dir = plugin_dir_path( __FILE__ );
$dir_url = plugin_dir_url( __FILE__ );

define( 'ACF_PATH', $dir . '/includes/acf/' );
define( 'ACF_URL', $dir_url . '/includes/acf/' );

include_once( ACF_PATH . 'acf.php' );

add_filter('acf/settings/url', 'acf_settings_url');
function acf_settings_url( $url ) {
  return ACF_URL;
}

#add_filter('acf/settings/show_admin', 'acf_settings_show_admin');
function acf_settings_show_admin( $show_admin ) {
  return false;
}

add_filter('acf/settings/save_json', 'custom_acf_json_save_point');
function custom_acf_json_save_point( $path ) {
  $path = ACF_PATH . '/acf-json';
  return $path;
}

add_filter('acf/settings/load_json', 'custom_acf_json_load_point');
function custom_acf_json_load_point( $paths ) {
  unset($paths[0]);
  $paths[] = ACF_PATH . '/acf-json';
  return $paths;
}

add_filter('acf/prepare_field/name=enem_simulator_shortcode', 'acf_prepare_field_enem_simulator_shortcode');
function acf_prepare_field_enem_simulator_shortcode( $field ) {
  $rows = get_field('enem_simulator_settings', 'options' );
  $pos = strpos( $field['name'], 'row-' );
  $substring = substr( $field['name'], $pos + 4 );
  $pos = strpos( $substring, '][' );
  $row = substr( $substring, 0, $pos );
  $name = sanitize_title( $rows[ $row ]['setting_name'] );
  $field['value'] = "[enem-simulator name=$name]";
  $field['readonly'] = true;
  return $field;
}