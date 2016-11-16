<?php get_header(); ?>

<section id="thr_main" class="content_wrapper clearfix">

<?php if(thr_get_option('show_progressbar')) : ?>
	<div class="page-progress"><span></span></div>
<?php endif; ?>
<?php $layout = thr_get_single_layout(); ?>
<?php get_template_part('sections/loops/single-layout-'.$layout); ?>

</section>
	
<?php get_footer(); ?>