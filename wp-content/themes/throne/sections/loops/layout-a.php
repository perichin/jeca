

	
<?php global $thr_sidebar_opts; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('layout_a'); ?>>

	<div class="entry-header">

		<h2 class="entry-title"><a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a></h2>
		
		<?php if($meta = thr_get_meta_data('layout-a')): ?>
			<div class="entry-meta">
				<?php echo $meta; ?>
			</div>
		<?php endif; ?>
	</div>

<?php $format = get_post_format(); ?>

<?php if(!$format || (thr_get_option('lay_a_content_type') == 'excerpt' && $format != 'image') ) : ?>

	<?php if(thr_get_option('lay_a_fimg') && $image = thr_featured_image('thr-layout-a', $thr_sidebar_opts['use_sidebar'])): ?>
	<div class="entry-image featured_image">
		<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
		<?php echo $image; ?>
		<span class="featured_item_overlay"></span>
		<?php if($icon = thr_post_format_icon()) :?>
		<span class="featured_icon">
			<i class="icon-<?php echo $icon; ?>"></i>
		</span>
		<?php endif; ?>
		</a>
	</div>
	<?php endif;?>

<?php endif; ?>

<?php if($format == 'image' && has_post_thumbnail() && thr_get_option('lay_a_fimg')) : ?>

<div class="entry-image featured_image">
	<?php $full_img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); ?>
	<?php $size = $thr_sidebar_opts['use_sidebar'] ? '' : '-nosid'; ?>
	<a href="<?php echo esc_url($full_img[0]); ?>" class="thr_image_format">
	<?php echo the_post_thumbnail('thr-layout-a'.$size); ?>
	<span class="featured_item_overlay"></span>
	<?php if($icon = thr_post_format_icon()) :?>
		<span class="featured_icon">
			<i class="icon-<?php echo $icon; ?>"></i>
		</span>
	<?php endif; ?>
	</a>
</div>

<?php endif; ?>
  	<?php if (!is_single()) : ?>
	<div class="entry-content">
		<?php if(thr_get_option('lay_a_content_type') == 'excerpt'): ?>
				<?php if( $excerpt = thr_get_excerpt()) {echo '<p>'.$excerpt.'</p>'; } ?>
			<?php else: ?>
				<?php global $more; $more = 0; ?>
				<?php the_content(__thr('read_more')); ?>
			<?php endif; ?>	
	</div>
	<?php endif;?>
	

	<div class="entry-footer">

		<?php if(thr_get_option('lay_a_rm')) : ?>
			<?php if( (thr_get_option('lay_a_content_type') == 'excerpt') || (thr_get_option('lay_a_content_type') == 'content' && thr_has_more_tag()) ) : ?>
				<div class="meta-item">
					<a href="<?php echo esc_url(get_permalink()); ?>" class="read_more_button"><i class="icon-arrow-right"></i><?php echo __thr('read_more'); ?></a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(thr_get_option('lay_a_share')) : ?>
		<div class="meta-item meta-item-share">
			<div class="meta-item-wrapper">
				<?php get_template_part('sections/share-list'); ?>
			</div>
		</div>
		<?php endif; ?>
		
	</div>

<div class="clear"></div>	
</article>
