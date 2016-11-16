<?php
/*-----------------------------------------------------------------------------------*/
/*	Register Theme Sidebars
/*-----------------------------------------------------------------------------------*/

function thr_register_sidebars() {
	/* Default Sidebar */

	register_sidebar(
		array(
			'id' => 'thr_default_sidebar',
			'name' => __( 'Default Sidebar', THEME_SLUG ),
			'description' => __( 'This is default sidebar.', THEME_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title"><span>',
			'after_title' => '</span></h4>'
		)
	);

	/* Default Sticky Sidebar */
	register_sidebar(
		array(
			'id' => 'thr_default_sticky_sidebar',
			'name' => __( 'Default Sticky Sidebar', THEME_SLUG ),
			'description' => __( 'This is default sticky sidebar. Sticky means that it will be always pinned to top while you are scrolling through your website content.', THEME_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title"><span>',
			'after_title' => '</span></h4>'
		)
	);

	/* Footer Sidebar */
	register_sidebar(
		array(
			'id' => 'thr_footer_sidebar',
			'name' => __( 'Footer', THEME_SLUG ),
			'description' => __( 'This is horizontal sidebar to use in footer area.', THEME_SLUG ),
			'before_widget' => '<div id="%1$s" class="widget footer-col '.thr_get_option( 'footer_columns' ).' %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widget-title"><span>',
			'after_title' => '</span></h4>'
		)
	);

	$custom_sidebars = thr_get_option( 'add_sidebars' );
	if ( $custom_sidebars ) {
		for ( $i = 1; $i <= $custom_sidebars; $i++ ) {
			register_sidebar(
				array(
					'id' => 'thr_custom_sidebar_'.$i,
					'name' => __( 'Additional Sidebar', THEME_SLUG ).' '.$i,
					'description' => '',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h4 class="widget-title"><span>',
					'after_title' => '</span></h4>'
				)
			);
		}
	}


}



?>