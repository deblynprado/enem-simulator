<?php

if( function_exists('acf_add_options_page') ) {
  
	acf_add_options_page(array(
		'page_title' 	=> __( 'Settings', 'enem-simulator' ) ,
		'menu_title'	=> __( 'Settings', 'enem-simulator' ),
		'menu_slug' 	=> 'enem-simulator-settingns',
		'parent_slug'	=> 'enem-simulator-main',
		'redirect'		=> false
	));
	
}