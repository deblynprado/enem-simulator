<?php 
function question_cpt() {
  $labels = array(
    'name'               => _x( 'Questions', 'question type general name', 'enem-simulator' ),
    'singular_name'      => _x( 'Question', 'question type singular name', 'enem-simulator' ),
    'add_new'            => _x( 'Add New', 'book', 'enem-simulator' ),
    'add_new_item'       => __( 'Add New Question', 'enem-simulator' ),
    'edit_item'          => __( 'Edit Question', 'enem-simulator' ),
    'new_item'           => __( 'New Question', 'enem-simulator' ),
    'all_items'          => __( 'All Questions', 'enem-simulator' ),
    'view_item'          => __( 'View Question', 'enem-simulator' ),
    'search_items'       => __( 'Search Questions', 'enem-simulator' ),
    'not_found'          => __( 'No questions found', 'enem-simulator' ),
    'not_found_in_trash' => __( 'No question found in the Trash', 'enem-simulator' ), 
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
    'name'              => _x( 'Question Categories', 'taxonomy general name', 'enem-simulator' ),
    'singular_name'     => _x( 'Question Category', 'taxonomy singular name', 'enem-simulator' ),
    'search_items'      => __( 'Search Question Categories', 'enem-simulator' ),
    'all_items'         => __( 'All Question Categories', 'enem-simulator' ),
    'parent_item'       => __( 'Parent Question Category', 'enem-simulator' ),
    'parent_item_colon' => __( 'Parent Question Category:', 'enem-simulator' ),
    'edit_item'         => __( 'Edit Question Category', 'enem-simulator' ), 
    'update_item'       => __( 'Update Question Category', 'enem-simulator' ),
    'add_new_item'      => __( 'Add New Question Category', 'enem-simulator' ),
    'new_item_name'     => __( 'New Question Category', 'enem-simulator' ),
    'menu_name'         => __( 'Question Categories', 'enem-simulator' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'question_category', 'question', $args );
}
add_action( 'init', 'question_taxonomy', 0 );

add_action( 'admin_menu', 'enem_simulator_menu' );
function enem_simulator_menu() { 
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
    'edit-tags.php?taxonomy=question_category&post_type=question'
  );
}