<?php
/*-----------------------------------------------------------------------------------*/
/*	Define Theme Vars
/*-----------------------------------------------------------------------------------*/

define( 'THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'THEME_URI', trailingslashit( get_template_directory_uri() ) );
define( 'THEME_NAME', 'Throne' );
define( 'THEME_SLUG', 'throne' );
define( 'THEME_VERSION', '1.6.1' );
define( 'THEME_OPTIONS', 'thr_settings' );
define( 'JS_URI', trailingslashit( THEME_URI . 'js' ) );
define( 'CSS_URI', trailingslashit( THEME_URI . 'css' ) );
define( 'IMG_DIR', trailingslashit( THEME_DIR . 'images' ) );
define( 'IMG_URI', trailingslashit( THEME_URI . 'images' ) );

if ( !isset( $content_width ) ) {
	$content_width = 730;
}


/*-----------------------------------------------------------------------------------*/
/*	After Theme Setup
/*-----------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'thr_theme_setup' );
function thr_theme_setup() {

	/* Load frontend scripts and styles */
	add_action( 'wp_enqueue_scripts', 'thr_load_scripts' );

	/* Load admin scripts and styles */
	add_action( 'admin_enqueue_scripts', 'thr_load_admin_scripts' );

	/* Register sidebars */
	add_action( 'widgets_init', 'thr_register_sidebars' );

	/* Register menus */
	add_action( 'init', 'thr_register_menus' );

	/* Register widgets */
	add_action( 'widgets_init', 'thr_register_widgets' );

	/* Add thumbnails support */
	add_theme_support( 'post-thumbnails' );

	/* Add theme support for title tag (since wp 4.1) */
	add_theme_support( 'title-tag' );

	/* Add image sizes */
	$image_sizes = thr_get_image_sizes();
	$image_sizes_opt = thr_get_option( 'image_sizes' );
	foreach ( $image_sizes as $id => $size ) {
		if ( isset( $image_sizes_opt[$id] ) && $image_sizes_opt[$id] ) {
			add_image_size( $id, $size['w'], $size['h'], $size['crop'] );
		}
	}

	/* Add post formats support */
	add_theme_support( 'post-formats', array(
			'audio', 'gallery', 'image', 'video'
		) );

	/* Support for HTML5 */
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );

	/* Automatic Feed Links */
	add_theme_support( 'automatic-feed-links' );

}


/* Load frontend styles */
function thr_load_styles() {

	//Load fonts
	$fonts = thr_generate_font_links();
	if ( !empty( $fonts ) ) {
		foreach ( $fonts as $k => $font ) {
			wp_register_style( 'thr_font_'.$k, $font, false, THEME_VERSION, 'screen' );
			wp_enqueue_style( 'thr_font_'.$k );
		}
	}

	//Load main css file
	wp_register_style( 'thr_style', THEME_URI . 'style.css', false, THEME_VERSION, 'screen' );
	wp_enqueue_style( 'thr_style' );

	//Append dynamic css
	$thr_dynamic_css = thr_generate_dynamic_css();
	wp_add_inline_style( 'thr_style', $thr_dynamic_css );

	//Enqueue font awsm icons if css is not already included via plugin
	if ( !wp_style_is( 'mks_shortcodes_fntawsm_css', 'enqueued' ) ) {
		wp_register_style( 'thr_font_awesome', CSS_URI . 'font-awesome.min.css', false, THEME_VERSION, 'screen' );
		wp_enqueue_style( 'thr_font_awesome' );
	}

	//Enqueue simple line icons if css is not already included via plugin
	if ( !wp_style_is( 'mks_shortcodes_simple_line_icons', 'enqueued' ) ) {
		wp_register_style( 'thr_icons', CSS_URI . 'simple-line-icons.css', false, THEME_VERSION, 'screen' );
		wp_enqueue_style( 'thr_icons' );
	}

	//Load responsive css
	if ( thr_get_option( 'responsive_mode' ) ) {
		wp_register_style( 'thr_responsive', CSS_URI . 'responsive.css', false, THEME_VERSION, 'screen' );
		wp_enqueue_style( 'thr_responsive' );
	}

	//Load RTL css
	if ( thr_get_option( 'rtl_mode' ) ) {
		wp_register_style( 'thr_rtl', CSS_URI . 'rtl.css', false, THEME_VERSION, 'screen' );
		wp_enqueue_style( 'thr_rtl' );
	}


}


/* Load frontend scripts */
function thr_load_scripts() {

	thr_load_styles();

	wp_enqueue_script( 'thr_match_height', JS_URI . 'jquery.matchHeight.js', array( 'jquery' ), THEME_VERSION, true );
	wp_enqueue_script( 'thr_responsive_menu', JS_URI . 'jquery.sidr.js', array( 'jquery' ), THEME_VERSION, true );

	if ( thr_get_option( 'lightbox' ) || thr_get_option( 'lightbox_content_img' ) ) {
		wp_enqueue_script( 'thr_magnific_popup', JS_URI . 'jquery.magnific-popup.min.js', array( 'jquery' ), THEME_VERSION, true );
	}

	wp_enqueue_script( 'thr_fitjs', JS_URI . 'jquery.fitvids.js', array( 'jquery' ), THEME_VERSION, true );
	wp_enqueue_script( 'thr_images_loaded', JS_URI . 'imagesloaded.pkgd.min.js', array( 'jquery' ), THEME_VERSION, true );
	wp_enqueue_script( 'thr_sticky', JS_URI . 'affix.js', array( 'jquery' ), THEME_VERSION, true );
	wp_enqueue_script( 'thr_custom', JS_URI . 'custom.js', array( 'jquery' ), THEME_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$thr_js_settings = thr_get_js_settings();
	wp_localize_script( 'thr_custom', 'thr_js_settings', $thr_js_settings );
}

/* Load admin scripts and styles */
function thr_load_admin_scripts() {
	global $pagenow;

	wp_register_style( 'thr_admin_css', CSS_URI . 'admin.css', false, THEME_VERSION, 'screen' );
	wp_enqueue_style( 'thr_admin_css' );

}

/* Support localization */
load_theme_textdomain( THEME_SLUG, THEME_DIR . 'languages' );


/*-----------------------------------------------------------------------------------*/
/*	Theme Includes
/*-----------------------------------------------------------------------------------*/


/* Helpers and utility functions */
require_once 'include/helpers.php';

/* Menus */
require_once 'include/menus.php';

/* Sidebars */
require_once 'include/sidebars.php';

/* Widgets */
require_once 'include/widgets.php';

/* Add custom metaboxes for standard post types */
require_once 'include/metaboxes.php';

/* Snippets (modify/add some special features to this theme) */
require_once 'include/snippets.php';

/* Include AJAX action handlers */
require_once 'include/ajax.php';

/* Include plugins (required or recommended for this theme) */
require_once 'include/plugins.php';

/* Theme Options */
require_once 'include/options.php';

?>