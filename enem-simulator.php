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
  $maximumTime = get_field('maximum_time', 'option');
  $alertTime = get_field('alert_time', 'option');
  wp_localize_script( 'enem-simulator', 'enem_simulator',
      array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'maximum_time' => $maximumTime,
        'alert_time' => $alertTime,
      )
  );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

function enem_simulator_shortcode( $atts ) {
  ob_start();
  include ( 'includes/partials/content-simulator.php' );
  return ob_get_clean();
}
add_shortcode( 'enem-simulator', 'enem_simulator_shortcode' );

function enem_simulator_get_categories() {
  $option = get_field( 'question_categories', 'option' ); 

  $categories = [];

  foreach ($option as $value) {
    $categories[] = [
                'slug' => $value[ 'question_category' ]->slug,
                'name' => $value[ 'question_category' ]->name,
    ];
  }

  return $categories;
}

function enem_simulator_get_questions($slug, $orderby) {
  $args = array(
    'orderby' => $orderby,
    'post_type' => 'question',
    'tax_query' => array(
        array(
          'taxonomy' => 'question_category',
          'field' => 'slug',
          'terms'    => $slug,
        ),
    ),
  );

  return new WP_Query( $args );
}

function enem_simulator_get_question_category_callback() {
  $categories = enem_simulator_get_categories();
  
  if ( isset($_POST['category'] )) 
    $category = $_POST['category'];

  foreach ($categories as $key => $value) {
    
    $questions = enem_simulator_get_questions($value['slug'], 'rand');
  
    $index = 0;
    $categoryName = $value['name'];
  
    if ( $questions->have_posts( ) ) {

      ?>
  
      <div class="content-question" data-category-index="<?php echo $key ?>" 
          id="<?php echo $value['slug'] ?>" <?php echo $category == $value['slug'] ? '' : 'style="display:none;"' ?> >
  
      <?php while ( $questions->have_posts() ) {  
  
        $questions->the_post();
  
        $fields = get_field( 'text_options', get_the_ID() ); 
        shuffle( $fields );
  
        include ( 'includes/partials/content-question.php' );
        
        $index++;
      }
  
      ?>
      </div>
      <?php
    }
    wp_reset_postdata();
  }

  wp_die();
}

add_action( 'wp_ajax_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );

function enem_simulator_get_nav_callback() {
  $categories = enem_simulator_get_categories();
  
  foreach ($categories as $key => $value) {
    
    $questions = enem_simulator_get_questions($value['slug'], 'name');
  
    $index = 0;
  
    if ( $questions->have_posts( ) ) {

      ?>
      <div class="content-category m-4" data-category-index="<?php echo $key ?>">
        <h5><?php echo $value['name'] ?></h5>
        <div class="progress mt-4 progress-category">
          <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
        <p class="mt-3"><?php echo __('Click on the question to navigate', 'enem-simulator') ?></p>
        <div class="question-nav">
      <?php while ( $questions->have_posts() ) {  
  
        $questions->the_post();
        ?>

        <div class="d-inline p-4 border <?php echo $index == 0 ? 'rounded-left' : ''; ?> 
          <?php echo ($index+1) == $questions->found_posts ? 'rounded-right' : ''; ?>">
          <a href="#"><?php echo $index+1; ?></a>
        </div>

        <?php
        
        $index++;
      }
  
      ?>
      </div>
      </div>
      <?php
    }
    wp_reset_postdata();
  }

  wp_die();
}

add_action( 'wp_ajax_enem_simulator_get_nav', 'enem_simulator_get_nav_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_get_nav', 'enem_simulator_get_nav_callback' );