</main>
<div class="clear"></div>
<footer id="footer" class="footer_wrapper full_width">
	
	<?php if(thr_get_option('footer_display')) : ?>
	<div class="content_wrapper">
		<?php dynamic_sidebar('thr_footer_sidebar'); ?>
	</div>
	<?php endif; ?>
	
	<?php if(thr_get_option('enable_copyright')) : ?>
	<div id="copy_area" class="copy_area full_width">
		
		<div class="content_wrapper">
			<div class="left">
				<?php echo thr_get_option('footer_copyright'); ?>
			</div>		
			
			<?php 
				if(has_nav_menu('thr_footer_menu') && thr_get_option('enable_footer_menu')) {
						wp_nav_menu( array( 'theme_location' => 'thr_footer_menu', 'menu' => 'thr_footer_menu', 'menu_class' => 'shl-footer-menu', 'menu_id' => 'thr_footer_menu', 'container' => false , 'depth' => 1) );
				}
			?>
		</div>
	</div>
	<?php endif; ?>
</footer>

<?php if(thr_get_option('body_style') == 'boxed'): ?>
</div>
</div>
<?php endif; ?>
<?php if(is_single() && thr_get_option('show_share')):?>
<div class="meta-share meta-item <?php if(thr_get_option('body_style') == 'boxed'): ?> is_bxd <?php endif; ?>">
	<?php get_template_part('sections/share-list'); ?>
</div>
<?php endif; ?>

<?php if(thr_get_option('scroll_to_top')): ?>
<a href="javascript:void(0)" id="back-top"><i class="fa fa-angle-up"></i></a>
<?php endif; ?>




<?php wp_footer(); ?>
</body>
</html>