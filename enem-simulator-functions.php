<?php

function enem_simulator_import_questions_callback() {
  if( isset( $_FILES )) 
    $file = $_FILES[ 'file' ][ 'tmp_name' ];
  $row = 0;
  if( $file ) {
    if( ( $handle = fopen( $file, 'r' )) !== FALSE ) {
      while( ( $data = fgetcsv( $handle, 0, ";" )) !== FALSE ) {
        if( $row !== 0 ) {
          $num = count( $data );
          $categoryID = enem_simulator_get_question_category_ID( $data[2] );
          if( $categoryID ) {
            $question = array(
              'post_title' =>  $data[0],
              'post_content' => $data[1],
              'post_type' => 'question',
              'tax_input' => array(
                'question_category' => array( $categoryID ),
              ),
              'post_status' => 'publish',
              'post_author' => 1,
            );
            $the_post_id = wp_insert_post( $question, $wp_error );
            update_field( 'field_5eb02790f5dc1', 'Text Options', $the_post_id ); //type_of_anser
            update_field( 'field_5ecd5537b4bdc', $data[9] , $the_post_id ); //weight_ansewer
            if ( $data[10] )
              update_field( 'field_5eb02ac918c41', 'active', $the_post_id ); //active_question
            for ($c=0; $c < 5; $c++) {
              $correctAnswer = [];
              if( $data[8] == ( $c + 1 ) ) 
                $correctAnswer = array( "correct" );
              add_row( 'field_5eb028ccf5dc5' , array(
                'field_5eb028e1f5dc6' => $data[ $c + 3 ], 
                'field_5eb02909f5dc7' => $correctAnswer,
              ), $the_post_id); //options;
            }
          }
        }
        $row++;
      }
    }
  }
  $return = array(
    'message' => __('Posts imported successfuly!', 'enem-simulator' )
  );
  wp_send_json( $return );
}

add_action( 'wp_ajax_enem_simulator_import_questions', 'enem_simulator_import_questions_callback' );

function enem_simulator_import_settings_callback() {
  $categories = [ [1,2], [3,4], [5,6], [7,8], [9,10] ];
  if( isset( $_FILES ))
    $file = $_FILES[ 'file' ][ 'tmp_name' ];
  $row = 0;
  if( $file ) {
    if( ( $handle = fopen( $file, 'r' )) !== FALSE ) {
      while( ( $data = fgetcsv( $handle, 0, ";" )) !== FALSE ) {
        if( $row !== 0 ) {
          $num = count( $data );
          add_row( 'field_5ec6ba18308e9', array(
            'field_5ec6bc1a99efe' => $data[0], //settings name
            'field_5eb29ac0e6678' => $data[11], // max time
            'field_5eb29b71d9526' => $data[12], //end test time alert
            'field_5eb29c298ef2a' => $data[13],  //end teste activated
            'field_5eb29cb28ef2b' => $data[14], //activated change questions alert 
            'field_5ecea8c39cfe9' => $data[15], //weight proficiency
            'field_5eb29dff8ef2c' => $data[16], //initial message
          ), 'option'); //settings;
          if ( have_rows('field_5ec6ba18308e9', 'option') ) {
            while ( have_rows('field_5ec6ba18308e9', 'option') ) { 
              the_row();
              $name = get_sub_field('field_5ec6bc1a99efe');
              if ( $name ===  $data[0] ) {
                for ($i=0; $i < 5; $i++) { 
                  $categoryID = enem_simulator_get_question_category_ID( $data[$categories[$i][0]] );
                  if ( $categoryID ) {
                    update_sub_row( 'field_5eb1d416462f6', $i+1, array(
                      'field_5eb1d53667fbf' => $categoryID, //category
                      'field_5eb1d4c267fbe' => $data[$categories[$i][1]], //amount
                      ) );
                  }
                }                      
              }
            }
          }
        }
        $row++;
      }
    }
  }
  $return = array(
    'message' => __('Settings imported successfuly!', 'enem-simulator' )
  );
  wp_send_json( $return );
}

add_action( 'wp_ajax_enem_simulator_import_settings', 'enem_simulator_import_settings_callback' );

function enem_simulator_get_question_category_ID($name) {
  global $wpdb;
  $result = $wpdb->get_results( "SELECT t.term_id FROM $wpdb->terms AS t
                                INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
                                WHERE tt.taxonomy = 'question_category' AND t.name = '$name'" );
  if( $result[0] )
    return $result[0]->term_id;
}

function enem_simulator_export_questions_callback() {
  $template=false;
  if( isset( $_GET['template'] ))
    $template = $_GET['template'];

  $filename = 'export_questions.csv';
  $delimiter = ";";
  $header_row = array(
    'NOME DA QUESTÃO',
    'DESCRIÇÃO DA QUESTÃO',
    'NOME DA DISCIPLINA',
    'ALTERNATIVA (A)',
    'ALTERNATIVA (B)',
    'ALTERNATIVA (C)',
    'ALTERNATIVA (D)',
    'ALTERNATIVA (E)',
    'ALTERNATIVA CORRETA',
    'PESO (0 - 10)',
    'STATUS DA QUESTÃO (ATIVO - INATIVO)',
  );

  ob_start();

  if ( !$template ) {
    $args = array(
      'orderby' => 'name',
      'post_type' => 'question',
      'post_status' => 'publish'
    );
    $questions = new WP_Query( $args );

    if ( $questions->have_posts( ) ) {
      while ( $questions->have_posts() ) {
        $questions->the_post();
        $category = get_the_terms( the_ID(), 'question_category' )[0]->slug;
        $options = get_field( 'text_options', get_the_ID() );
        $weight = get_field( 'weight_answer', get_the_ID() );
        $status = get_field( 'active_question', get_the_ID() )[0] ? 1 : 0;
        
        foreach ($options as $key =>  $value) {
          if( $value[ 'correct_answer' ] ) {
            $correctAnswer = $key + 1;
          }
        } 
        $content[] = array(
          wp_strip_all_tags( get_the_title() ),
          wp_strip_all_tags( get_the_content() ),
          $category,
          $options[0]['text_answer'],
          $options[1]['text_answer'],
          $options[2]['text_answer'],
          $options[3]['text_answer'],
          $options[4]['text_answer'],
          $correctAnswer,
          $weight,
          $status
        );
      } 
    }
  }

  ob_clean();

  $fh = @fopen( 'php://output', 'w' );
  fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
  header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
  header( 'Content-Description: File Transfer' );
  header( 'Content-type: text/csv' );
  header( "Content-Disposition: attachment; filename={$filename}" );
  header( 'Expires: 0' );
  header( 'Pragma: public' );

  fputcsv( $fh, $header_row, $delimiter );

  foreach ($content as $value) {
    fputcsv( $fh, $value, $delimiter );   
  }
  
  fclose( $fh );
  
  ob_end_flush();
  
  wp_die();
  die();
}

add_action( 'wp_ajax_enem_simulator_export_questions', 'enem_simulator_export_questions_callback' );

function enem_simulator_export_settings_callback() {
  $template=false;
  if( isset( $_GET['template'] ))
    $template = $_GET['template'];

  $filename = 'export_settings.csv';
  $delimiter = ";";
  $header_row = array(
    'NOME',
    'NOME DA DISCIPLINA (1)',
    'QTD DE QUESTÕES DA DISCIPLINA (1)',
    'NOME DA DISCIPLINA (2)',
    'QTD DE QUESTÕES DA DISCIPLINA (2)',
    'NOME DA DISCIPLINA (3)',
    'QTD DE QUESTÕES DA DISCIPLINA (3)',
    'NOME DA DISCIPLINA (4)',
    'QTD DE QUESTÕES DA DISCIPLINA (4)',
    'NOME DA DISCIPLINA (5)',
    'QTD DE QUESTÕES DA DISCIPLINA (5)',
    'TEMPO MÁXIMO',
    'TEMPO DE ALERTA DE FIM DE PROVA',
    'ATIVAR ALERTA DE FIM DE PROVA',
    'ATIVAR ALERTA DE MUDANÇA DE QUESTÃO',
    'PESO',
    'MENSAGEM INICIAL',
  );

  ob_start();

  if ( !$template ) {
    $filds = get_field( 'enem_simulator_settings', 'option' );
    foreach ($filds as $value) {
      $categories = $value[ 'question_categories' ];
      $content[] = array(
        $value[ 'setting_name' ],
        $categories[0][ 'question_category' ]->slug,
        $categories[0][ 'category_amount' ],
        $categories[1][ 'question_category' ]->slug,
        $categories[1][ 'category_amount' ],
        $categories[2][ 'question_category' ]->slug,
        $categories[2][ 'category_amount' ],
        $categories[3][ 'question_category' ]->slug,
        $categories[3][ 'category_amount' ],
        $categories[4][ 'question_category' ]->slug,
        $categories[4][ 'category_amount' ],
        $value[ 'maximum_time' ], 
        $value[ 'alert_time' ], 
        $value[ 'end_test_alert' ], 
        $value[ 'test_change_alert' ], 
        $value[ 'weight_proficiency' ], 
        $value[ 'initial_message' ], 
      );
    }
  }

  ob_clean();

  $fh = @fopen( 'php://output', 'w' );
  fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
  header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
  header( 'Content-Description: File Transfer' );
  header( 'Content-type: text/csv' );
  header( "Content-Disposition: attachment; filename={$filename}" );
  header( 'Expires: 0' );
  header( 'Pragma: public' );

  fputcsv( $fh, $header_row, $delimiter );

  foreach ($content as $value) {
    fputcsv( $fh, $value, $delimiter );   
  }
  
  fclose( $fh );
  
  ob_end_flush();
  
  wp_die();
  die();
}

add_action( 'wp_ajax_enem_simulator_export_settings', 'enem_simulator_export_settings_callback' );