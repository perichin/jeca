<?php $related_posts = thr_get_related_posts(); ?>

<?php if ( $related_posts->have_posts() ): ?>

	<section id="related-posts-<?php the_ID();?>" class="related-box author-box post-box">

		<h3 class="comment_title underlined_heading"><span><?php echo __thr( 'related_title' ); ?></span></h3>

		<div class="posts_wrapper">
			<?php while ( $related_posts->have_posts() ): $related_posts->the_post(); ?>
				<?php get_template_part('sections/loops/'.thr_get_option('related_layout')); ?>
			<?php endwhile; ?>

		</div>

	</section>

<?php endif; ?>

<?php wp_reset_postdata(); ?>