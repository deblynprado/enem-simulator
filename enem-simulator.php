<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* Enem Simulator
*
* @package           EnemSimulator
* @author            Deblyn Prado, Walfrido Oliveira
* @copyright         2019 Deblyn Prado, Walfrido Oliveira
* @license           GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name:       Enem Simulator
* Plugin URI:        https://github.com/deblynprado/enem-simulator
* Description:       Allows your users to generate random tests and check their knowledge.
* Version:           1.0.0
* Requires at least: 5.0
* Requires PHP:      7.2
* Author:            Deblyn Prado, Walfrido Oliveira
* Author URI:        https://example.com
* Text Domain:       plugin-slug
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

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