<!DOCTYPE html>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]>    <html class="no-js gt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  
  <!-- Meta Tags -->
  	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1, maximum-scale=1">

<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<?php
global $thr_sidebar_opts;
$thr_sidebar_opts = thr_get_current_sidebar();
$sidebar_class = $thr_sidebar_opts['use_sidebar'] ? 'thr_body_sidebar_'.$thr_sidebar_opts['use_sidebar'] : 'no_sidebar';
$sticky_on = thr_get_option('sticky_header') ? ' sticky_on' : '';
?>

<body <?php body_class($sidebar_class.$sticky_on); ?>>

<?php if($body_bg_img = thr_get_option_media('body_bg_img')): ?>
<div class="body_bg_img">
	<div class="overlay_bg_div"></div>
	<img src="<?php echo esc_url($body_bg_img); ?>" alt="<?php bloginfo('name'); ?>"/>
</div>
<?php endif; ?>

<?php if(thr_get_option('body_style') == 'boxed'): ?>
<div class="thr_boxed">
	<div class="thr_boxed_wrapper">
<?php endif; ?>

<?php if(thr_get_option('sticky_header')) : ?>
<div id="sticky_header" class="header-sticky"><div class="content_wrapper"></div></div>
<?php endif; ?>

<header id="header" class="header full_width clearfix">
	<div class="header-main">
		<?php if($header_bg_img = thr_get_option_media('header_bg_img')): ?>
			<div class="header_bg_img"><img src="<?php echo esc_url($header_bg_img); ?>" alt="<?php bloginfo('name'); ?>"/></div>
		<?php endif; ?>
		<div class="content_wrapper">		
			<?php $header_layout = thr_get_option('header_main'); ?>
			<?php get_template_part('sections/headers/header-main-'.$header_layout); ?>
		</div>

	</div>
	<?php if(in_array($header_layout, array(2,3))): ?>
	<div class="header-bottom">
		<div class="content_wrapper">
			<?php get_template_part('sections/headers/header-bottom'); ?>
		</div>
	</div>
	<?php endif; ?>
</header>

<main id="main_content" class="clearfix">