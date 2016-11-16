<?php 
$show_subheader =  true;
	
if(is_search()) {
	$subheader_content = '<h1>'.__thr('search_results_for').get_search_query().'</h1><span class="arch_line"></span>';
} else if(is_tag()) {
	$subheader_content = '<h1>'.__thr('tag').single_tag_title('',false).'</h1><span class="arch_line"></span>';
	$description = tag_description();
	if(!empty($description)){
		$subheader_content.= wpautop($description);
	}
} else if(is_day()) {
	$subheader_content = '<h1>'.__thr('archive').get_the_date().'</h1><span class="arch_line"></span>';
} else if(is_month()) {
	$subheader_content = '<h1>'.__thr('archive').get_the_date('F Y').'</h1><span class="arch_line"></span>';
} else if(is_year()) {
	$subheader_content = '<h1>'.__thr('archive').get_the_date('Y').'</h1><span class="arch_line"></span>';
} else if(is_404()) {
	$show_subheader = false;
} else if(is_category()) {
	$subheader_content = '<h1 class="category-heading-title">'.__thr('category').single_cat_title('',false).'</h1><span class="arch_line"></span>';
	if(thr_get_option('category_feed_link')){
		  $obj = get_queried_object();
			$subheader_content.= '<a href="'.get_category_feed_link($obj->term_id).'" class="thr_category_link"><i class="icon-rss"></i></a>';
	}
	$description = category_description();
	if(!empty($description)){
		$subheader_content.= wpautop($description);
	}
} else if(is_author()) {
	$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
	$subheader_content = '<h1>'.__thr('author').$curauth->display_name.'</h1><span class="arch_line"></span>';
} else if(is_tax()){
	$subheader_content = '<h1>'.single_term_title('',false).'</h1><span class="arch_line"></span>';

} else if( is_home() && ($posts_page = get_option('page_for_posts')) && !is_page_template('template-home.php')){
	
	$subheader_content = '<h1>'.get_the_title($posts_page).'</h1>';
	$description = thr_get_page_meta($posts_page, 'desc');
	if(!empty($description)){
		$subheader_content.= wpautop($description);
	}
	
} else {
	$show_subheader = false;
}
?>

<?php if($show_subheader): ?>
	<div id="archive_title" class="full_width archive-title">
		<div class="content_wrapper">
			<?php echo $subheader_content; ?>
		</div>
	</div>
<?php endif; ?>
