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

define("PLUGIN_FILE_URL", __FILE__);

include ( 'enem-simulator-install.php' );
include ( 'enem-simulator-functions.php' );
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
  wp_register_script( 'enem-simulator', plugins_url( 'includes/assets/js/enem-simulator.js', __FILE__ ), array( 'jquery', 'bootstrap-js'), null, true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

function enem_simulator_setting_name( $name = null ) {
  static $settingName = '';
  if ( is_null( $name ) ) return $settingName;
  $settingName = $name;
  return $settingName;
}

function enem_simulator_shortcode( $atts ) {
  ob_start();
  enem_simulator_setting_name( $atts[ 'name' ] );
  wp_enqueue_script('enem-simulator');
  wp_localize_script( 'enem-simulator', 'enem_simulator',
      array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'maximum_time' => enem_simulator_get_option( 'maximum_time' ),
        'alert_time' => enem_simulator_get_option( 'alert_time' ),
        'end_test_alert' => enem_simulator_get_option( 'end_test_alert' ),
        'test_change_alert' => enem_simulator_get_option( 'test_change_alert' ),  
        'the_ids' => enem_simulator_get_posts_id(),
        'categories' => enem_simulator_get_categories(),
        'weight_proficiency' => enem_simulator_get_option( 'weight_proficiency' ),  
      )
  );
  $categories = enem_simulator_get_categories();
  include ( 'includes/partials/content-simulator.php' );
  return ob_get_clean();
}
add_shortcode( 'enem-simulator', 'enem_simulator_shortcode' );

function enem_simulator_get_option( $name ) {
  $filds = get_field( 'enem_simulator_settings', 'option' );
  $settingName = enem_simulator_setting_name();
  foreach ($filds as $value) {
    $slug = sanitize_title( $value[ 'setting_name' ] );
   if ( $slug == $settingName ) {
    $fild = $value;
    break;
   }
  }

  return $fild[ $name ];
}

function enem_simulator_get_posts_id() {
  $categories = enem_simulator_get_categories();
  $theIDs = [];
  foreach ($categories as $key => $value) :
    $questions = enem_simulator_get_questions( $value['slug'], 'rand', $value['category_amount'] );
    if ( $questions->have_posts( ) ) : 
      while ( $questions->have_posts() ) :  
        $questions->the_post();
        if ( !get_field( 'active_question', get_the_ID() )) continue;
        $theIDs[$value['slug']][] = get_the_ID(); 
      endwhile; 
    endif;
    wp_reset_postdata();
  endforeach;
  return $theIDs;
}

function enem_simulator_get_categories() {
  $option = enem_simulator_get_option( 'question_categories' ); 
  $categories = [];
  
  if ( is_array( $option ) ) {
    foreach ($option as $value) {
      $questions = enem_simulator_get_questions( $value[ 'question_category' ]->slug );
      if ( $questions->have_posts( ) ) 
        $categories[] = [
                    'slug' => $value[ 'question_category' ]->slug,
                    'name' => $value[ 'question_category' ]->name,
                    'category_amount' => $value[ 'category_amount' ],
        ];
    }
  }

  return $categories;
}

function enem_simulator_get_questions($slug, $orderby = 'name', $limit = 0) {
  $args = array(
    'orderby' => $orderby,
    'post_type' => 'question',
    'posts_per_page' => $limit,
    'post_status' => 'publish',
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
  if ( isset($_POST['categories'] )) 
    $categories = $_POST['categories'];

  if ( isset($_POST['category'] )) 
    $category = $_POST['category'];

  if ( isset( $_POST['the_ids'] ))
    $theIDs = $_POST['the_ids'];

  $questionCount = 1;

  foreach ($categories as $key => $value) :
    $index = 0;
    $categoryName = $value['name'];
    $slug = $value[ 'slug' ];
    $posts = $theIDs[ $value[ 'slug' ] ];
  ?>
    <div class="content-question" data-category-index="<?php echo $key ?>" 
      id="<?php echo $slug ?>" <?php echo $category == $slug ? '' : 'style="display:none;"' ?> >
    <?php foreach ( $posts as $value ) : 
      global $post;
      $post = get_post($value);
      $option = get_field( 'type_of_anser', get_the_ID() );
      if ( $option == 'Image Options' )
        $fields = get_field( 'image_options', get_the_ID() ); 
      else if ( $option == 'Text Options' )
        $fields = get_field( 'text_options', get_the_ID() ); 
      shuffle( $fields );
      include ( 'includes/partials/content-question.php' );
      $index++;
      $questionCount++;
    endforeach; ?>
    </div>
    <?php wp_reset_postdata();
  endforeach;

  wp_die();
}

add_action( 'wp_ajax_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_get_question_category', 'enem_simulator_get_question_category_callback' );

function enem_simulator_get_nav_callback() {
  if ( isset($_POST['categories'] )) 
    $categories = $_POST['categories'];

  if ( isset( $_POST['the_ids'] ))
    $theIDs = $_POST['the_ids'];

  foreach ($categories as $key => $value) :
    $index = 0;
    $posts = $theIDs[ $value[ 'slug' ] ];
    $slug = $value[ 'slug' ];
    $name = $value[ 'name' ];
  ?>
    <div class="content-category m-4" data-category-index="<?php echo $key ?>">
      <h5><?php echo $name ?></h5>
      <div class="progress mt-4 progress-category">
        <div class="progress-bar progress-bar-nav" id="propress-bar-<?php echo $key ?>" role="progressbar" style="width: 0%;" 
          aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" data-category-name="<?php echo $slug; ?>">0%</div>
      </div>
      <p class="mt-3 text-uppercase"><?php echo __('Click on the question to navigate', 'enem-simulator') ?></p>
      <div class="question-nav">
      <?php foreach ( $posts as $value ) :  
        global $post;
        $post = get_post($value); ?>
        <a href="#" class="question-nav-item p-4 border" data-question-id="<?php echo get_the_ID(); ?>" 
          data-category-name="<?php echo $slug; ?>"><?php echo $index+1; ?></a>
        <?php
        $index++;
      endforeach; ?>
      </div>
    </div>
    <?php wp_reset_postdata();
  endforeach;

  wp_die();
}

add_action( 'wp_ajax_enem_simulator_get_nav', 'enem_simulator_get_nav_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_get_nav', 'enem_simulator_get_nav_callback' );

function enem_simulator_add_user_register_callback() {
  global $wpdb;

  if ( isset( $_POST[ 'name' ] ) ) 
    $name = sanitize_text_field( $_POST[ 'name' ] );

  if ( isset( $_POST[ 'mail' ] ) ) 
    $mail = sanitize_text_field( $_POST[ 'mail' ] );

  if ( isset( $_POST[ 'whatsapp' ] ) ) 
    $whatsapp = sanitize_text_field( $_POST[ 'whatsapp' ] );

  $tableName = get_option( 'enem_simulator_user_table_name' );

  $result = $wpdb->insert( $tableName, 
                array( 
                  'name' => $name,
                  'mail' => $mail,
                  'whatsapp' => $whatsapp,
                  'created_at' => current_time( 'mysql' )
                ),
                array( '%s',  '%s', '%s', '%s')
              );
  echo $result;
}

add_action( 'wp_ajax_enem_simulator_add_user_register', 'enem_simulator_add_user_register_callback' );
add_action( 'wp_ajax_nopriv_enem_simulator_add_user_register', 'enem_simulator_add_user_register_callback' );