<section id="post-author-<?php the_ID(); ?>" class="author-box">


	<h3 class="comment_title underlined_heading"><span><?php echo __thr('about_author'); ?></span>
		<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="thr_author_link"><?php echo __thr('view_all_posts'); ?></a>
	<?php if (get_the_author_meta('url')) {?> <a href="<?php the_author_meta('url'); ?>" target="_blank" class="thr_author_link"><?php echo __thr('author_website'); ?></a><?php } ?></h3>


	<div class="data-image">
		<?php echo get_avatar( get_the_author_meta('ID'), 112 ); ?>
	</div>
	
	<div class="data-content">
		<h4 class="author-title"><?php the_author_meta('display_name'); ?>
		<div class="thr_author_links">
			<?php $user_social = thr_get_social(); ?>			
			<?php foreach($user_social as $soc_id => $soc_name): ?>
				<?php if($social_meta = get_the_author_meta($soc_id)) : ?>
					<a href="<?php echo $social_meta; ?>" target="_blank" class="fa fa-<?php echo $soc_id.' soc_squared'; ?>"></a>
				<?php endif; ?>			
			<?php endforeach; ?>					
		</div>
		</h4>

		
		<?php echo wpautop(get_the_author_meta('description')); ?>
		<div class="clear"></div>
	</div>

</section>