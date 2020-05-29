<?php

if( function_exists('acf_add_options_page') ) {
  
	acf_add_options_page(array(
		'page_title' 	=> __( 'Simulado do Enem', 'enem-simulator' ) ,
		'menu_title'	=> __( 'Configurações', 'enem-simulator' ),
		'menu_slug' 	=> 'enem-simulator-settingns',
		'parent_slug'	=> 'enem-simulator-main',
    'redirect'		=> false,
    'update_button' => __('Salvar', 'acf'),
    'updated_message' => __("Configurações salvas", 'acf'),
  ));
  
  acf_add_options_page(array(
		'page_title' 	=> __( '', 'enem-simulator' ) ,
		'menu_title'	=> __( 'Importar/Exportar', 'enem-simulator' ),
		'menu_slug' 	=> 'enem-simulator-import-export',
		'parent_slug'	=> 'enem-simulator-main',
    'redirect'		=> false,
    'update_button' => __('', 'acf'),
    'updated_message' => __("Configurações salvas", 'acf'),
	));
	
}