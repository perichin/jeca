<?php
/**
 * Template Name: Home Page
 */
?>
<?php get_header(); ?>

<?php
	
	
	//Check if we are on paginated home page
	if(is_front_page()){
		$hp_page = get_query_var('page');
	} else {
		$hp_page = get_query_var('paged');
	}

	//Check subheader area
	$subheader_content = false;

	if( (!thr_get_option('home_pag_wa_hide') && $hp_page >= 2) || ($hp_page < 2) ){
		global $post;
		$subheader_content = get_post_field('post_content', $post->ID);
		if(!empty($subheader_content)){
			$subheader_content = apply_filters('the_content', $subheader_content);
		}
	}
?>

<?php if($subheader_content && thr_get_option('home_wa_pos') == 'up'): ?>
	<div id="subheader_box" class="full_width">
		<div class="content_wrapper">
			<?php echo $subheader_content; ?>
		</div>
	</div>
<?php endif; ?>


<?php get_template_part('sections/featured','area'); ?>


<?php if($subheader_content && thr_get_option('home_wa_pos') == 'down'): ?>
	<div id="subheader_box" class="full_width">
		<div class="content_wrapper">
			<?php echo $subheader_content; ?>
		</div>
	</div>
<?php endif; ?>

<?php if( thr_get_option('home_display_posts') ) : ?>

<section id="thr_main" class="content_wrapper">

<?php global $thr_sidebar_opts; ?>
<?php if ( $thr_sidebar_opts['use_sidebar'] == 'left' ) { get_sidebar(); } ?>

<div class="main_content_wrapper">
	

<?php
	global $wp_query;
	$wp_query = thr_get_home_page_posts(); //copy hp posts query to main wp query to output in main loop
?>

<div class="posts_wrapper">
<?php if (have_posts()) : while (have_posts()) : the_post();?>
	<?php get_template_part('sections/loops/'.thr_get_option('home_page_layout')); ?>
<?php endwhile;?>
<?php else: ?>
	<?php get_template_part( 'sections/content', 'none' ); ?>
<?php endif; ?>
</div>

<?php get_template_part('sections/pagination'); ?>
	
<?php wp_reset_query(); ?>

</div>

<?php if ( $thr_sidebar_opts['use_sidebar'] == 'right' ) { get_sidebar(); } ?>
	
</section>

<?php endif; ?>


<?php get_footer(); ?>