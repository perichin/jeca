<article id="post-<?php the_ID(); ?>" <?php post_class('layout_d'); ?>>

	<?php if($image = thr_featured_image('thr-layout-d')): ?>
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

	<h2 class="entry-title"><a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo thr_get_title('layout-d'); ?></a></h2>
	
	<?php if($meta = thr_get_meta_data('layout-d')): ?>
		<div class="entry-meta">
			<?php echo $meta; ?>
		</div>
	<?php endif; ?>

		
<div class="clear"></div>	
</article>