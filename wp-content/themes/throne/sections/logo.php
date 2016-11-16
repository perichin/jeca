<div class="logo_wrapper">

	<?php 
		$logo_url = thr_get_option('logo_custom_url') ? esc_url(thr_get_option('logo_custom_url')) : home_url( '/' ); 
		$logo = thr_get_option('logo')
	?>
	
	<?php $title_tag = is_front_page() ? 'h1' : 'span'; ?>

	<<?php echo $title_tag;?> class="site-title">
		<a href="<?php echo esc_url( $logo_url ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" >
			<?php if(!empty($logo['url'])) : ?>
				<img src="<?php echo $logo['url']; ?>" alt="<?php bloginfo( 'name' ); ?>" />
			<?php else: ?>
				<?php bloginfo( 'name' ); ?>
			<?php endif; ?>
		</a>
	</<?php echo $title_tag;?>>

<?php if (thr_get_option('header_description')) { ?>
	<span class="site-desc">
		<?php echo get_bloginfo('description'); ?>
	</span>	
<?php } ?>	

</div>