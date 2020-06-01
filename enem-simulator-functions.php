<?php

function enem_simulator_import_callback() {
  if( isset( $_FILES )) {
    $file = $_FILES[ 'file' ][ 'tmp_name' ];
  }
  $row = 0;
  try {
    if( $file ) {
      if( ( $handle = fopen( $file, 'r' )) !== FALSE ) {
        while( ( $data = fgetcsv( $handle, 0, ";" )) !== FALSE ) {
          if( $row !== 0 ) {
            $num = count( $data );
            $question = array(
              'post_title' =>  $data[0],
              'post_content' => $data[1],
              'post_type' => 'question',
              'tax_input' => array(
                'question_category' => array( enem_simulator_get_question_category_ID( $data[2] ) ),
              ),
              'post_status' => 'publish',
              'post_author' => 1,
            );
            $the_post_id = wp_insert_post( $question, $wp_error );
            update_field( 'field_5eb02790f5dc1', 'Text Options', $the_post_id ); //type_of_anser
            update_field( 'field_5ecd5537b4bdc', $data[9] , $the_post_id ); //weight_ansewer
            update_field( 'field_5eb02ac918c41', 'active', $the_post_id ); //active_question
            for ($c=0; $c < 5; $c++) {
              $correctAnswer = [];
              if( $data[8] == ( $c + 1 ) ) 
                $correctAnswer = array( "correct" );
              add_row( 'field_5eb028ccf5dc5', array(
                'field_5eb028e1f5dc6' => $data[ $c + 3 ],
                'field_5eb02909f5dc7' => $correctAnswer,
              ), $the_post_id); //options;
            }
          }
          $row++;
        }
      }
    }
  } catch (\Throwable $th) {
    wp_send_json( array(
      'message' => $th->getMessage
    ));
  }
  $return = array(
    'message' => __('Posts imported successfuly!', 'enem-simulator' )
  );
  wp_send_json( $return );
}

add_action( 'wp_ajax_enem_simulator_import', 'enem_simulator_import_callback' );

function enem_simulator_get_question_category_ID($name) {
  global $wpdb;
  $result = $wpdb->get_results( "SELECT t.*, tt.* FROM $wpdb->terms AS t
                                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                                WHERE tt.taxonomy = 'question_category' AND t.name = '$name'" );
  if( $result[0] )
    return $result[0]->term_id;
}