<?php global $thr_sidebar_opts; ?>
<?php if ( $thr_sidebar_opts['use_sidebar'] == 'left' ) { get_sidebar(); } ?>
<div class="main_content_wrapper">

<div class="posts_wrapper layout_a">
	
<?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-header">

		<h1 class="entry-title"><?php the_title(); ?></h1>
	
		<?php if($meta = thr_get_meta_data('single')): ?>
			<div class="entry-meta">
				<?php echo $meta; ?>
			</div>
		<?php endif; ?>
	</div>
	
<?php $format = get_post_format(); ?>

<?php if(!$format) : ?>

	<?php if( thr_get_option('show_fimg') && $image = thr_featured_image('thr-layout-a', $thr_sidebar_opts['use_sidebar'])): ?>
	<div class="entry-image featured_image">
		<?php echo $image; ?>
		<?php if(thr_get_option('show_fimg_caption') && $caption = get_post(get_post_thumbnail_id())->post_excerpt) : ?>
			<div class="photo_caption"><?php echo $caption;  ?></div>
		<?php endif; ?>
	</div>
	<?php endif;?>

<?php endif; ?>

<?php if($format == 'image' && has_post_thumbnail()) : ?>

<div class="entry-image featured_image">
	<?php $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
	<?php $size = $thr_sidebar_opts['use_sidebar'] ? '' : '-nosid'; ?>
	<a href="<?php echo esc_url($full_img[0]); ?>" class="thr_image_format">
	<?php echo the_post_thumbnail('thr-layout-a'.$size); ?>
	<span class="featured_item_overlay"></span>
	</a>
	<?php if(thr_get_option('show_fimg_caption') && $caption = get_post(get_post_thumbnail_id())->post_excerpt) : ?>
		<div class="photo_caption"><?php echo $caption;  ?></div>
	<?php endif; ?>
</div>

<?php endif; ?>
  
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