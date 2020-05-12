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
include ( 'cpt-settings.php' );
include ( 'acf-options.php' );

function enqueue_styles() {
  wp_enqueue_style( 'bootstrap', plugins_url( 'includes/assets/bootstrap/css/bootstrap.min.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_styles' );

function enqueue_scripts() {
  wp_enqueue_script( 'jQuery' );
  wp_enqueue_script( 'enem-simulator', plugins_url( 'includes/assets/bootstrap/js/enem-simulator.js', __FILE__ ), null, null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

function enem_simulator_shortcode( $atts ) {

  include ( 'includes/partials/content-initial.php' );

  extract( shortcode_atts( 
    array(
          'categories' => ''
    ), $atts )); 

  if ( strpos( $categories, ',') !== false) {
      $categories = explode( ',', $categories );
  }

  $args = array(
    'post_type' => 'question',
    'tax_query' => array(
        array(
          'taxonomy' => 'question_category',
          'field' => 'slug',
          'terms'    => $categories,
        ),
    ),
  );

  $questions = new WP_Query( $args );

  if ( $questions->have_posts( ) ) {

    while ( $questions->have_posts() ) {  

      $questions->the_post();

      $fields = get_field( 'text_options', get_the_ID() ); 

      //include ( 'includes/partials/content-answer.php' );
    }
  }

  wp_reset_postdata();
    
}
add_shortcode( 'enem-simulator', 'enem_simulator_shortcode' );

function enem_simulator_get_question_category_callback() {

  if ( isset($_POST['category'] )) $categories[] = $_POST['category'];

  $args = array(
    'post_type' => 'question',
    'tax_query' => array(
        array(
          'taxonomy' => 'question_category',
          'field' => 'id',
          'terms'    => $categories,
        ),
    ),
  );

  $questions = new WP_Query( $args );

  if ( $questions->have_posts( ) ) {

    while ( $questions->have_posts() ) {  

      $questions->the_post();

      $fields = get_field( 'text_options', get_the_ID() ); 

      include ( 'includes/partials/content-answer.php' );
    }
  }

  wp_reset_postdata();

   wp_die();
}

add_action( 'wp_ajax_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );