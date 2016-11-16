<?php get_template_part( 'sections/logo'); ?>
<a class="nav-btn" id="nav-open-btn" href="#"><i class="fa fa-bars"></i></a>
<nav id="nav" class="main_navigation">
	<?php 
		if(has_nav_menu('thr_main_navigation_menu')) {
				wp_nav_menu( array( 'theme_location' => 'thr_main_navigation_menu', 'menu' => 'thr_main_navigation_menu', 'menu_class' => 'nav-menu', 'menu_id' => 'thr_main_navigation_menu', 'container' => false ) );
		} else { ?>
			<ul id="thr_header_nav" class="nav-menu"><li>
				<a href="<?php echo admin_url('nav-menus.php'); ?>"><?php _e('Click here to add navigation menu', THEME_SLUG); ?></a>
			</li></ul>
			
		<?php }
	?>
</nav>