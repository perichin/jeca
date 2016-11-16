<?php 
/*-----------------------------------------------------------------------------------*/
/*	Register WP3.0+ Menus
/*-----------------------------------------------------------------------------------*/

if( !function_exists( 'thr_register_menus' ) ) :
    function thr_register_menus() {
	    register_nav_menu('thr_main_navigation_menu', __( 'Main Navigation' , THEME_SLUG));
	    register_nav_menu('thr_footer_menu', __( 'Footer Menu' , THEME_SLUG));
	    register_nav_menu('thr_404_menu', __( '404 Menu' , THEME_SLUG));
    }
endif;

?>