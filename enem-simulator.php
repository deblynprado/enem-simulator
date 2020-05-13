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
* Domain Path:       /languages/
* License:           GPL v2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

include ( 'acf-settings.php' );
include ( 'cpt-settings.php' );
include ( 'acf-options.php' );

add_action( 'plugins_loaded', 'enem_simulator_load_text_domain' );
function enem_simulator_load_text_domain() {
  load_plugin_textdomain( 'enem-simulator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function enqueue_scripts() {
  wp_enqueue_style( 'bootstrap', plugins_url( 'includes/assets/bootstrap/css/bootstrap.min.css', __FILE__ ) );
  wp_enqueue_style( 'font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', null );
  wp_enqueue_style( 'enem-simulator', plugins_url( 'includes/assets/css/enem-simulator.css', __FILE__ ) );
  wp_enqueue_script( 'bootstrap-js', plugins_url( 'includes/assets/bootstrap/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ), null, true );
  wp_enqueue_script( 'enem-simulator', plugins_url( 'includes/assets/js/enem-simulator.js', __FILE__ ), array( 'jquery', 'bootstrap-js'), null, true );
  wp_localize_script( 'enem-simulator', 'enem_simulator',
      array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
      )
  );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

function enem_simulator_shortcode( $atts ) {
  include ( 'includes/partials/content-simulator.php' );
}
add_shortcode( 'enem-simulator', 'enem_simulator_shortcode' );

function enem_simulator_get_question_category_callback() {

  if ( isset($_POST['category'] )) {
    $categories[] = $_POST['category']['value'];
    $categoryName = $_POST['category']['name'];
  }

  $args = array(
    'orderby' => 'rand',
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

  $index = 0;

  if ( $questions->have_posts( ) ) {

    while ( $questions->have_posts() ) {  

      $questions->the_post();

      $fields = get_field( 'text_options', get_the_ID() ); 
      shuffle( $fields );

      include ( 'includes/partials/content-question.php' );
      
      $index++;
    }
  }
  wp_reset_postdata();

  wp_die();
}

add_action( 'wp_ajax_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );