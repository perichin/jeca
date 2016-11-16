<?php global $thr_sidebar_opts; ?>

<?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>

<div class="single_b content_wrapper">
	
	<?php if($image = thr_featured_image('thr-layout-a', false)): ?>
	
	<div class="entry-image">
		<?php echo $image; ?>

		<div class="featured_title_over">
		<?php if($meta = thr_get_meta_data('single')): ?>
			<div class="entry-meta">
				<?php echo $meta; ?>
			</div>
		<?php endif; ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>

		</div>

		<?php if($icon = thr_post_format_icon()) :?>
			<div class="featured_icon">
				<i class="icon-<?php echo $icon; ?>"></i>
			</div>
		<?php endif; ?>

		<?php if(thr_get_option('show_fimg_caption') && $caption = get_post(get_post_thumbnail_id())->post_excerpt) : ?>
			<div class="photo_caption"><?php echo $caption;  ?></div>
		<?php endif; ?>

	</div>	

	<?php endif;?>
</div>

<?php if ( $thr_sidebar_opts['use_sidebar'] == 'left' ) { get_sidebar(); } ?>

<div class="main_content_wrapper">


<div class="posts_wrapper layout_a">

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>

		<?php if(thr_get_option('show_tags') && has_tag()) : ?>
				<div class="meta-tags">
					<?php the_tags(false,'&nbsp;&nbsp;&nbsp;',false);?>
				</div>
		<?php endif; ?>

	</div>

	<?php if(thr_get_option('show_prev_next')): ?>
		<?php get_template_part('sections/prev-next'); ?>
	<?php endif;?>

	<?php if ( thr_get_option( 'show_related' ) ) : ?>
		<?php get_template_part( 'sections/related-box' ); ?>
	<?php endif; ?>


	 <?php if(thr_get_option('show_author_box')): ?>
		<?php get_template_part('sections/author-box'); ?>
	 <?php endif;?>
	
<div class="clear"></div>	
</article>

<?php endwhile; ?>
	
<?php endif; ?>
</div>
<?php comments_template( '', true ); ?>
</div>
<?php if ( $thr_sidebar_opts['use_sidebar'] == 'right' ) { get_sidebar(); } ?>