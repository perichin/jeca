<?php get_header(); ?>

<section id="thr_main" class="content_wrapper">

<?php global $thr_sidebar_opts; ?>
<?php if ( $thr_sidebar_opts['use_sidebar'] == 'left' ) { get_sidebar(); } ?>

<div class="main_content_wrapper">

<?php while ( have_posts() ) : the_post(); ?>

			
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="entry-header">		
	<h1 class="entry-title"><?php the_title(); ?></h1>
	</div>
	<?php if( thr_get_option('page_show_fimg') && has_post_thumbnail() ): ?>
	<div class="entry-image">
		<?php $img_size = $thr_sidebar_opts['use_sidebar'] ? 'thr-layout-a' : 'thr-layout-a-nosid'; ?>
		<?php the_post_thumbnail($img_size); ?>
	</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div>
	
<div class="clear"></div>	
			
</article><!-- #post -->

<?php if( thr_get_option('page_show_comments') ) : ?>
	<?php comments_template( '', true ); ?>
<?php endif; ?>

<?php endwhile; // end of the loop. ?>

</div>

<?php if ( $thr_sidebar_opts['use_sidebar'] == 'right' ) { get_sidebar(); } ?>

</section>
	
<?php get_footer(); ?>