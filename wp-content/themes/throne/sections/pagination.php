<?php if(get_next_posts_link() || get_previous_posts_link()) : ?>
<div class="clear"></div>
<nav id="pagination" class="pagination-wapper">
		<?php if(thr_get_option('pagination_type') == 'prev_next'): ?>

		<div class="prev_next_nav">
			<div class="post_previous newer_entries">
				<?php previous_posts_link(__thr('newer_entries')); ?>			
			</div>
			
			<div class="post_next older_entries">
				<?php next_posts_link(__thr('older_entries')); ?>
			</div>	
		</div>
			
		<?php else: ?>
			<?php thr_pagination(__thr('previous_posts'),__thr('next_posts')); ?>
		<?php endif;?>
</nav>
<?php endif; ?>