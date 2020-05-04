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
* Text Domain:       enem-simulator
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

include ( 'acf-settings.php' );

function question_cpt() {
  $labels = array(
    'name'               => _x( 'Questions', 'question type general name' ),
    'singular_name'      => _x( 'Question', 'question type singular name' ),
    'add_new'            => _x( 'Add New', 'book' ),
    'add_new_item'       => __( 'Add New Question' ),
    'edit_item'          => __( 'Edit Question' ),
    'new_item'           => __( 'New Question' ),
    'all_items'          => __( 'All Questions' ),
    'view_item'          => __( 'View Question' ),
    'search_items'       => __( 'Search Questions' ),
    'not_found'          => __( 'No questions found' ),
    'not_found_in_trash' => __( 'No question found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Questions'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Setup new Questions for test',
    'public'        => true,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail' ),
    'has_archive'   => true,
    'show_in_menu'  => 'enem-simulator-main'
  );
  register_post_type( 'question', $args ); 
}
add_action( 'init', 'question_cpt' );

function question_taxonomy() {
  $labels = array(
    'name'              => _x( 'Question Categories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Question Category', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Question Categories' ),
    'all_items'         => __( 'All Question Categories' ),
    'parent_item'       => __( 'Parent Question Category' ),
    'parent_item_colon' => __( 'Parent Question Category:' ),
    'edit_item'         => __( 'Edit Question Category' ), 
    'update_item'       => __( 'Update Question Category' ),
    'add_new_item'      => __( 'Add New Question Category' ),
    'new_item_name'     => __( 'New Question Category' ),
    'menu_name'         => __( 'Question Categories' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'question_taxonomy', 'question', $args );
}
add_action( 'init', 'question_taxonomy', 0 );

add_action('admin_menu', 'my_admin_menu'); 
function my_admin_menu() { 
    #add_submenu_page('edit.php', 'question', 'question', 'manage_options', 'edit-tags.phptaxonomy=question_taxonomy&post_type=question'); 
    add_menu_page(
      'Enem Simulator',
      __( 'Enem Simulator', 'enem-simulator' ),
      'manage_options',
      'enem-simulator-main',
      'my_menu_function'
    );

    add_submenu_page(
      'enem-simulator-main',
      'Add Question',
      __( 'Add Question', 'enem-simulator' ),
      'manage_options',
      'post-new.php?post_type=question'
    );

    add_submenu_page(
      'enem-simulator-main',
      'Question Categories',
      __( 'Question Categories', 'enem-simulator' ),
      'manage_options',
      'edit-tags.php?taxonomy=question_taxonomy&post_type=question'
    );

} 