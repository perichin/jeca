<?php

	/* Get option values to generate dynamic styles */
	
	$main_font = thr_get_option('main_font');
	$h_font = thr_get_option('h_font');
	$nav_font = thr_get_option('nav_font');
	$native_fonts = thr_get_native_fonts();

	//Header
	$header_height = thr_get_option('header_height') ? thr_get_option('header_height') : 100;
	$logo_position = thr_get_option('logo_position');
	$logo_top = isset($logo_position['padding-bottom']) ? absint( $logo_position['padding-bottom']) : 0;
	$logo_left = isset($logo_position['padding-right']) ? absint( $logo_position['padding-right']) : 0;
	$color_header_bg = thr_hex2rgba(thr_get_option('color_header_bg'),thr_get_option('header_bg_opacity'));
	$color_header_txt = thr_get_option('color_header_txt');
	$color_header_acc = thr_get_option('color_header_acc');
	$header_bg_pat = thr_get_option_media('header_bg_pat');
	$color_header_bottom_bg = thr_get_option('header_main') == 1 ? thr_get_option('color_header_bg') : thr_get_option('color_header_bottom_bg');
	$sticky_header_bg = thr_hex2rgba($color_header_bottom_bg, 0.9);

	//Body
	$color_body_bg = thr_hex2rgba(thr_get_option('color_body_bg'),thr_get_option('body_bg_opacity'));
	$color_body_txt = thr_get_option('color_body_txt');
	$body_bg_pat = thr_get_option_media('body_bg_pat');
	$color_body_boxed_bg = thr_get_option('color_body_boxed_bg');
	$body_margin = thr_get_option('body_top_margin');
	$body_top_margin = isset($body_margin['padding-bottom']) ? absint( $body_margin['padding-bottom']).'px' : 0;

	//Content
	$color_content_bg = thr_get_option('main_content_style') == 'default' ? 'transparent' : thr_get_option('color_content_bg');
	$color_content_txt = thr_get_option('color_content_txt');
	$color_content_txt_h = thr_get_option('color_content_txt_h');
	$color_content_meta = thr_get_option('color_content_meta');
	$color_content_acc = thr_get_option('color_content_acc');
	$color_content_arch_h = thr_get_option('color_content_arch_h');
	
	//Sidebar
	$color_sidebar_bg = thr_get_option('sidebar_style') == 'default' ? 'transparent' : thr_get_option('color_sidebar_bg');
	$color_sidebar_txt = thr_get_option('color_sidebar_txt');
	$color_sidebar_txt_h = thr_get_option('color_sidebar_txt_h');
	$color_sidebar_acc = thr_get_option('color_sidebar_acc');

	//Footer
	$color_footer_bg = thr_get_option('color_footer_bg');
	$color_footer_txt = thr_get_option('color_footer_txt');
	$color_footer_txt_h = thr_get_option('color_footer_txt_h');
	$color_footer_acc = thr_get_option('color_footer_acc');
?>

body,
.button_respond,
.thr_author_link {
	<?php if(!in_array($main_font['font-family'], $native_fonts)): ?>
	font-family: '<?php echo $main_font['font-family']; ?>';
	<?php else: ?>
	font-family: <?php echo $main_font['font-family']; ?>;
	<?php endif; ?>
	font-weight: <?php echo $main_font['font-weight']; ?>;
	<?php if(isset($main_font['font-style']) && !empty($main_font['font-style'])):?>
	font-style: <?php echo $main_font['font-style']; ?>;
	<?php endif; ?>
}
h1,h2,h3,h4,h5,h6,
.featured_posts_link,
.mks_author_widget h3{
	<?php if(!in_array($h_font['font-family'], $native_fonts)): ?>
	font-family: '<?php echo $h_font['font-family']; ?>';
	<?php else: ?>
	font-family: <?php echo $h_font['font-family']; ?>;
	<?php endif; ?>
	font-weight: <?php echo $h_font['font-weight']; ?>;	
	<?php if(isset($h_font['font-style']) && !empty($h_font['font-style'])):?>
	font-style: <?php echo $h_font['font-style']; ?>;
	<?php endif; ?>
}
#nav li a,
.site-title,
.site-title a,
.site-desc,
.sidr ul li a{
	<?php if(!in_array($nav_font['font-family'], $native_fonts)): ?>
		font-family: '<?php echo $nav_font['font-family']; ?>';
	<?php else: ?>
		font-family: <?php echo $nav_font['font-family']; ?>;
	<?php endif; ?>	
	font-weight: <?php echo $nav_font['font-weight']; ?>;	
	<?php if(isset($nav_font['font-style']) && !empty($nav_font['font-style'])):?>
		font-style: <?php echo $nav_font['font-style']; ?>;
	<?php endif; ?>	
}

body,
.overlay_bg_div{
	background-color: <?php echo $color_body_bg; ?>;
	<?php if(!empty($body_bg_pat)): ?>
		background-image: url('<?php echo esc_url($body_bg_pat); ?>');
	<?php endif; ?>
}
.arch_line,
.page-progress span{
	background: <?php echo $color_content_acc; ?>;
}
.thr_boxed_wrapper,
.is_bxd.meta-share{
	background-color: <?php echo $color_body_boxed_bg; ?>;
}
.thr_boxed{
	margin-top: <?php echo $body_top_margin; ?>;
}
::-webkit-input-placeholder {
   color:  <?php echo $color_header_txt; ?>;
}

:-moz-placeholder {
   color: <?php echo $color_header_txt; ?>;
}

::-moz-placeholder {
   color:  <?php echo $color_header_txt; ?>;
}

:-ms-input-placeholder {  
   color:  <?php echo $color_header_txt; ?>;
}
.site-title a,
#nav li a,
.header-main,
.search_header_form input[type="text"], .search_header_form input[type="text"]:focus,
.menu-item-has-children:after,
.sidr ul li span,
.sidr ul li a {
	color: <?php echo $color_header_txt; ?>;
}
#nav > ul > li:hover > a, 
#nav a:hover,
#nav li.current-menu-item > a,
#nav li.current_page_item > a,
#nav li.current-menu-item.menu-item-has-children:after,
#nav li.current_page_item.menu-item-has-children:after,
.sidr ul li:hover > a, .sidr ul li:hover > span,
#sidr-existing-content li.sidr-class-current_page_item > a,
#sidr-existing-content li.sidr-class-current_page_item > .thr_menu_parent,
#sidr-existing-content li.sidr-class-current-menu-item > a,
#sidr-existing-content li.sidr-class-current-menu-item > .thr_menu_parent{
	color: <?php echo $color_header_acc; ?>;
}

.sidr ul li:hover > a, .sidr ul li.active > a, .sidr ul li.sidr-class-active > a,
.sidr ul li ul li:hover > a, .sidr ul li ul li.active > a, .sidr ul li ul li.sidr-class-active > a {
  -webkit-box-shadow: inset 2px 0 0 0px <?php echo $color_header_acc; ?>;
  -moz-box-shadow: inset 2px 0 0 0px <?php echo $color_header_acc; ?>;
  box-shadow: inset 2px 0 0 0px <?php echo $color_header_acc; ?>;	
}

.menu-item-has-children:after{
	border-top-color: <?php echo $color_header_txt; ?>;
}
li.menu-item-has-children:hover:after{
	color: <?php echo $color_header_acc; ?>;
}
.header-main {
	height: <?php echo $header_height; ?>px;
	background-color: <?php echo thr_get_option('color_header_bg'); ?>;
	background-color: <?php echo $color_header_bg; ?>;
	background-repeat:repeat;
	<?php if(!empty($header_bg_pat)): ?>
		background-image: url('<?php echo esc_url($header_bg_pat); ?>');
	<?php endif; ?>
}
.sidr{
		background-color: <?php echo $color_header_bottom_bg; ?>;
}
#sticky_header{
	background-color: <?php echo $color_header_bottom_bg; ?>;
	background:<?php echo $sticky_header_bg; ?>;
}
.header-main .search_header_form{
	background-color: transparent;
}

.header-main .search_header_form input[type="text"]{
	top:<?php echo ($header_height-48)/2; ?>px;;
}
#nav li a{
	padding: <?php echo ($header_height-22)/2; ?>px 3px <?php echo ($header_height-20)/2; ?>px;
}

.header-bottom,
#nav .sub-menu{
	background-color: <?php echo $color_header_bottom_bg; ?>;
}
.header_ads_space{
	margin: <?php echo ($header_height-90)/2; ?>px 0;
}
.logo_wrapper{
	top: <?php echo $logo_top; ?>px;
	left: <?php echo $logo_left; ?>px;
}
.menu-item-has-children:after{
	margin: <?php echo ($header_height-12)/2; ?>px 0 0 2px;	
}
.main_content_wrapper{
	background-color: <?php echo $color_content_bg; ?>;
}
.thr_content_wrapped .meta-share{
	background-color: <?php echo thr_get_option('color_content_bg'); ?>;
}

.main_content_wrapper .single .entry-title, .page-template-default .entry-title,
.main_content_wrapper .entry-title a,
.main_content_wrapper h1,
.main_content_wrapper h2,
.main_content_wrapper h3,
.main_content_wrapper h4, 
.main_content_wrapper h5,
.main_content_wrapper h6,
#subheader_box h1,
#subheader_box h2,
#subheader_box h3,
#subheader_box h4,
#subheader_box h5,
#subheader_box h6{
	color: <?php echo $color_content_txt_h; ?>;
}
.main_content_wrapper,
#subheader_box p{
	color: <?php echo $color_content_txt; ?>;
}
.meta-item,
.meta-item a,
.comment-metadata time,
.comment-list .reply a,
.main_content_wrapper .button_respond,
li.cat-item,
.widget_archive li,
.widget_recent_entries ul span{
	color: <?php echo $color_content_meta; ?>;
}
.meta-item i,
.entry-title a:hover,
a,
.comment-metadata time:before,
.button_respond:hover,
.button_respond i,
.comment-list .reply a:before,
.comment-list .reply a:hover,
.meta-item a:hover,
.error404 h1,
.thr_content_wrapped .meta-share.meta-item  i{
	color: <?php echo $color_content_acc; ?>;
}
.error404 .entry-content .nav-menu li a{
	background: <?php echo $color_content_acc; ?>;
}
.underlined_heading span{
	border-bottom-color: <?php echo $color_content_acc; ?>;
}
blockquote{
	border-color: <?php echo $color_content_acc; ?>;
}
.comment-reply-title:after,
#submit,
.meta-item .read_more_button, .thr_button, input[type="submit"],
.current, .page-numbers:hover, #pagination .post_previous a:hover, #pagination .post_next a:hover, .load_more_posts a:hover,
.f_read_more,
.wp-caption .wp-caption-text,
.entry-content .mejs-container .mejs-controls, 
.entry-content .mejs-embed, 
.entry-content .mejs-embed body,
.comment-list li.bypostauthor > .comment-body:after,
.comment-list li.comment-author-admin > .comment-body:after{
	background: <?php echo $color_content_acc; ?>;
}
.entry-content .mejs-controls .mejs-time-rail .mejs-time-total,
.entry-content .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-total,
.entry-content .mejs-controls .mejs-time-rail .mejs-time-loaded{
	background: <?php echo $color_body_bg; ?>;
}
.entry-content .mejs-controls .mejs-time-rail .mejs-time-current,
.entry-content .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
	background: <?php echo thr_hex2rgba($color_content_acc, 0.5); ?>;
}
.current, .page-numbers:hover, #pagination .post_previous a:hover, #pagination .post_next a:hover, .load_more_posts a:hover{
	box-shadow: inset 0 0 0 1px <?php echo $color_content_acc; ?>;
}
.thr_sidebar_wrapped #sidebar,
.thr_widget_wrapped #sidebar .widget,
.thr_sidebar_wrapped .affix,
.thr_sidebar_wrapped .affix-bottom{
	background: <?php echo $color_sidebar_bg; ?>;
}
#sidebar .widget-title,
#sidebar h1,
#sidebar h2,
#sidebar h3,
#sidebar h4,
#sidebar h5,
#sidebar h6{
	color: <?php echo $color_sidebar_txt_h; ?>;
}
#sidebar .widget-title span{
	border-bottom-color: <?php echo $color_sidebar_acc; ?>;
}
.widget_tag_cloud a,
#sidebar .widget a,
li.recentcomments:before,
#sidebar .thr_posts_widget.widget .entry-meta a:hover {
	color: <?php echo $color_sidebar_acc; ?>;
}
#sidebar{
	color: <?php echo $color_sidebar_txt; ?>;
}
.footer_wrapper{
	background-color: <?php echo $color_footer_bg; ?>
}
.footer_wrapper p,
.footer_wrapper,
.footer_wrapper .widget{
	color: <?php echo $color_footer_txt; ?>;
}
.footer_wrapper a{
	color: <?php echo $color_footer_acc; ?>;
}
.footer_wrapper h1,
.footer_wrapper h2,
.footer_wrapper h3,
.footer_wrapper h4,
.footer_wrapper h5,
.footer_wrapper h6,
.footer_wrapper .widget-title{
	color: <?php echo $color_footer_txt_h; ?>;
}
.archive-title,
.category-heading-title,
#archive_title p,
.archive-title h1{
	color: <?php echo $color_content_arch_h; ?>;
}
<?php
  /* Apply uppercase options */
	$text_upper = thr_get_option('text_upper');
	if(!empty($text_upper)){
		foreach($text_upper as $text_class => $val){
			if($val)
			 echo '.'.$text_class.'{text-transform: uppercase;}';
		}
	}
?>

<?php
  	/* Check for expanded share */
	$share_expand = array('layout_a', 'layout_b', 'layout_c');
	foreach($share_expand as $layout){
		if(thr_get_option($layout.'_share_expand')){
			echo '.'.$layout.' .soc_sharing ul{ display: block; }';
			echo '.'.$layout.' .soc_sharing .thr_share_button {border-color: rgba(0,0,0,0.1);}';
			echo '.'.$layout.' .soc_sharing{margin-top:2px;}';
		}
	}

?>