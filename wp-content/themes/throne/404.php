<?php get_header(); ?>

<section id="thr_main" class="content_wrapper">

<div class="main_content_wrapper">


  <article class="entry-content">      

		<h1><?php echo __thr('404_text'); ?></h1>
		<h2><?php echo __thr('dont_panic_text'); ?></h2>


		<h5><?php echo __thr('no_worries'); ?></h5>
    <h5><?php echo __thr('check_links'); ?></h5>
    <?php 
	    if(has_nav_menu('thr_404_menu')) {
	    	wp_nav_menu( array( 'theme_location' => 'thr_404_menu', 'menu' => 'thr_404_menu', 'menu_class' => 'nav-menu', 'menu_id' => 'thr_404_menu', 'container' => false ));
		}
    ?>
	
	</article><!-- .entry-content -->
<div class="clear"></div>		



</div>
<!-- END CONTENT -->	


</section>
<!-- END PRIMARY -->


<?php get_footer(); ?>