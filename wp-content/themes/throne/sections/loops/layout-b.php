

<article id="post-<?php the_ID(); ?>" <?php post_class('layout_b'); ?>>

	<?php if($image = thr_featured_image('thr-layout-b')) : ?>
	
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

		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr(get_the_title()); ?>"><?php echo thr_get_title('layout-b'); ?></a></h2>
		
		<?php if($meta = thr_get_meta_data('layout-b')): ?>
			<div class="entry-meta">
				<?php echo $meta; ?>
			</div>
		<?php endif; ?>

	<div class="entry-content">

		<?php if (!is_single()) : ?>
			<?php if( $excerpt = thr_get_excerpt('layout-b')) {echo '<p>'.$excerpt.'</p>'; } ?>
		<?php endif;?>
		
		<?php if(thr_get_option('lay_b_rm')) : ?>
		<div class="meta-item">
			<a href="<?php echo esc_url(get_permalink()); ?>" class="read_more_button"><i class="icon-arrow-right"></i><?php echo __thr('read_more'); ?></a>
		</div>
		<?php endif; ?>

		<?php if(thr_get_option('lay_b_share')) : ?>
		<div class="meta-item meta-item-share meta-item-share-small">
			<div class="meta-item-wrapper">
				<?php get_template_part('sections/share-list'); ?>
			</div>
		</div>
		<?php endif; ?>
		
	</div>


<div class="clear"></div>	
</article>
