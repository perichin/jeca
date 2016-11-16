<?php 

/*-----------------------------------------------------------------------------------*/
/*	Include functions to handle ajax calls
/*-----------------------------------------------------------------------------------*/


/* Update latest theme version */
add_action('wp_ajax_thr_update_version', 'thr_update_version');

if(!function_exists('thr_update_version')):
function thr_update_version(){
	update_option('thr_theme_version',THEME_VERSION);
	die();
}
endif;

/* Update latest theme version */
add_action('wp_ajax_thr_hide_welcome', 'thr_hide_welcome');

if(!function_exists('thr_hide_welcome')):
function thr_hide_welcome(){
	update_option('thr_welcome_box_displayed', true);
	die();
}
endif;


?>