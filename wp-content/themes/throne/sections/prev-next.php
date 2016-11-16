<nav id="post-nav" class="single_post_navigation">
<?php 
	$in_same_cat = thr_get_option('prev_next_cat') ? true : false;
	$prev = get_previous_post($in_same_cat); 
	$next = get_next_post($in_same_cat);
	global $thr_sidebar_opts;
?>
<div class="prev_next_nav">
	<?php if($prev) : ?>
		<div class="single_prev_next single_post_previous">
			<?php $img = thr_featured_image('thr-layout-c', $thr_sidebar_opts['use_sidebar'], $prev->ID); ?>

			<div class="prev_next_link">
				<?php previous_post_link('%link',$img.'<span class="featured_item_overlay"></span><span class="featured_title_over"><span class="meta-item"><i class="icon-arrow-left"></i>'.__thr('previous_post').'</span><span class="featured_posts_link">%title</span></span>',$in_same_cat); ?>
			</div>
		</div>
	<?php endif; ?>
	
	<?php if($next) : ?>
		<div class="single_prev_next single_post_next">
			<?php $img = thr_featured_image('thr-layout-c', $thr_sidebar_opts['use_sidebar'], $next->ID); ?>
			<div class="prev_next_link">
				<?php next_post_link('%link', $img.'<span class="featured_item_overlay"></span><span class="featured_title_over"><span class="meta-item"><i class="icon-arrow-right"></i>'.__thr('next_post').'</span><span class="featured_posts_link">%title</span></span>',$in_same_cat); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
</nav>