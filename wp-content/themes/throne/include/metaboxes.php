<?php
/*-----------------------------------------------------------------------------------*/
/*	Add Metaboxes
/*-----------------------------------------------------------------------------------*/ 

add_action( 'load-post.php', 'thr_meta_boxes_setup' );
add_action( 'load-post-new.php', 'thr_meta_boxes_setup' );

/* Meta box setup function. */
if(!function_exists('thr_meta_boxes_setup')) :
function thr_meta_boxes_setup() {
	global $typenow;
	if($typenow == 'page'){
		add_action( 'add_meta_boxes', 'thr_load_page_metaboxes' );
		add_action( 'save_post', 'thr_save_page_metaboxes', 10, 2);
	}
	
	if($typenow == 'post'){
		add_action( 'add_meta_boxes', 'thr_load_post_metaboxes' );
		add_action( 'save_post', 'thr_save_post_metaboxes', 10, 2);
	}
}
endif;

/* Add page metaboxes */
if(!function_exists('thr_load_page_metaboxes')) :
	function thr_load_page_metaboxes() {
			
		  /* Sidebar metabox */
		  add_meta_box(
		    'thr_sidebar',
		    __('Sidebar',THEME_SLUG),
		    'thr_sidebar_metabox',
		    'page',
		    'side',
		    'default'
		  );
	}
endif;

/* Add post metaboxes */
if(!function_exists('thr_load_post_metaboxes')) :
	function thr_load_post_metaboxes() {
					  
		   /* Sidebar metabox */
		  add_meta_box(
		    'thr_sidebar',
		    __('Sidebar',THEME_SLUG),
		    'thr_sidebar_metabox',
		    'post',
		    'side',
		    'default'
		  );

		  /* Layout metabox */
		  add_meta_box(
		    'thr_layout',
		    __('Layout',THEME_SLUG),
		    'thr_layout_metabox',
		    'post',
		    'side',
		    'default'
		  );
		  
	}
endif;


/* Create Sidebars Metabox */
if(!function_exists('thr_sidebar_metabox')) :
	function thr_sidebar_metabox($object, $box) {
	  $defaults = array('use_sidebar' => 'inherit', 'sidebar' => 'inherit', 'sticky_sidebar' => 'inherit');
	  $thr_meta = get_post_meta($object->ID,'_thr_meta',true);
	  $thr_meta = wp_parse_args( (array) $thr_meta, $defaults );
	  $sidebars_lay = thr_get_sidebar_layouts(true);
	  $sidebars = thr_get_sidebars_list(true);
?>
	  <p class="description"><?php _e('Use sidebar?',THEME_SLUG); ?></p>
	  	<ul>
	  	<?php foreach($sidebars_lay as $id => $name): ?>
	  		<li>
	  			<input type="radio" name="thr[use_sidebar]" id="<?php echo $id; ?>" value="<?php echo $id; ?>" <?php checked($id,$thr_meta['use_sidebar']);?>/> <label for="<?php echo $id; ?>"><?php echo $name; ?></label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	  <?php if(!empty($sidebars)): ?>
	  <p class="description"><?php _e('Choose standard sidebar to display',THEME_SLUG); ?></p>
	  	<p><select name="thr[sidebar]" class="widefat">
	  	<?php foreach($sidebars as $id => $name): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['sidebar']);?>><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php _e('Choose sticky sidebar to display',THEME_SLUG); ?></p>
	  	<p><select name="thr[sticky_sidebar]" class="widefat">
	  	<?php foreach($sidebars as $id => $name): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['sticky_sidebar']);?>><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select></p>

	  <?php endif; ?>
	  <?php
	}
endif;

/* Create Layout Metabox */
if(!function_exists('thr_layout_metabox')) :
	function thr_layout_metabox($object, $box) {
	  wp_nonce_field( __FILE__ , 'thr_page_nonce' );
	  $defaults = array('layout' => 0);
	  $thr_meta = get_post_meta($object->ID,'_thr_meta',true);
	  $thr_meta = wp_parse_args( (array) $thr_meta, $defaults );
	  ?>
	 <p class="howto"><?php _e('Choose layout for this post', THEME_SLUG);?></p>	
	<ul>
	  <li>
	  	<input type="radio" name="thr[layout]" value="inherit" <?php checked(0,$thr_meta['layout']);?>/><?php _e('Inherit (global single post option)',THEME_SLUG); ?>
	  </li>
	  <li>
	  	<input type="radio" name="thr[layout]" value="a" <?php checked('a',$thr_meta['layout']);?>/><?php _e('Classic',THEME_SLUG); ?>
	  </li>
	  <li>
	  	<input type="radio" name="thr[layout]" value="b" <?php checked('b',$thr_meta['layout']);?>/><?php _e('Featured',THEME_SLUG); ?>
	  </li>
	 </ul>
	  <?php
	}
endif;


/* Save Page Meta */
if(!function_exists('thr_save_page_metaboxes')) :
	function thr_save_page_metaboxes($post_id, $post ) {
		  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		    return;
		    
			if(isset($_POST['thr_page_nonce'])){
		  	if ( !wp_verify_nonce( $_POST['thr_page_nonce'], __FILE__  ) )
		    	return;
		  }
		   
		  if($post->post_type == 'page' && isset($_POST['thr'])) {
		  	$post_type = get_post_type_object( $post->post_type );
		  	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		    return $post_id;
		
		  	$thr_meta = array();

		  	$thr_meta['use_sidebar'] = isset($_POST['thr']['use_sidebar']) ? $_POST['thr']['use_sidebar'] : 0;
		  	$thr_meta['sidebar'] = isset($_POST['thr']['sidebar']) ? $_POST['thr']['sidebar'] : 0;
		  	$thr_meta['sticky_sidebar'] = isset($_POST['thr']['sticky_sidebar']) ? $_POST['thr']['sticky_sidebar'] : 0;
				
		  	update_post_meta($post_id, '_thr_meta', $thr_meta);

			}
	}
endif;

/* Save Post Meta */
if(!function_exists('thr_save_post_metaboxes')) :
	function thr_save_post_metaboxes($post_id, $post ) {
		
		  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		    return;
		    
			if(isset($_POST['thr_post_nonce'])){
		  	if ( !wp_verify_nonce( $_POST['thr_post_nonce'], __FILE__  ) )
		    	return;
		  }
		   
		 
		  if($post->post_type == 'post' && isset($_POST['thr'])) {
		  	$post_type = get_post_type_object( $post->post_type );
		  	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		    return $post_id;

			$thr_meta = array();

		  	$thr_meta['use_sidebar'] = isset($_POST['thr']['use_sidebar']) ? $_POST['thr']['use_sidebar'] : 0;
		  	$thr_meta['sidebar'] = isset($_POST['thr']['sidebar']) ? $_POST['thr']['sidebar'] : 0;
		  	$thr_meta['sticky_sidebar'] = isset($_POST['thr']['sticky_sidebar']) ? $_POST['thr']['sticky_sidebar'] : 0;
		  	$thr_meta['layout'] = isset($_POST['thr']['layout']) ? $_POST['thr']['layout'] : 0;  	
				
		  	update_post_meta($post_id, '_thr_meta', $thr_meta);
		  	
			}
	}
endif;


/* Add metaboxes to category */

if(!function_exists('thr_category_add_meta_fields')) :
	function thr_category_add_meta_fields() {
	  $thr_meta = thr_get_category_meta();
	  $sidebars_lay = thr_get_sidebar_layouts(true);
	  $sidebars = thr_get_sidebars_list(true);
	  $layouts = thr_get_post_layouts(true);
	  $fa_layouts = thr_featured_area_layouts(true);
?>
	  
	  <div class="form-field">
	  	<label><?php _e('Featured area layout', THEME_SLUG); ?></label>
	  	<select name="thr[fa_layout]" class="widefat">
	  	<?php foreach($fa_layouts as $id => $v): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['fa_layout']);?>/><?php echo $v['title']; ?></option>
	  	<?php endforeach; ?>
	  </select>
	   <p class="description"><?php _e('Choose which featured area layout to display for this category',THEME_SLUG); ?></p>
	 </div>

	  <div class="form-field">
	  	<label><?php _e('Posts layout', THEME_SLUG); ?></label>
	  	<select name="thr[layout]" class="widefat">
	  	<?php foreach($layouts as $id => $v): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['layout']);?>/><?php echo $v['title']; ?></option>
	  	<?php endforeach; ?>
	  </select>
	   <p class="description"><?php _e('Choose which posts layout to display for this category',THEME_SLUG); ?></p>
	 </div>

	 <div class="form-field">
	  	<label><?php _e('Sidebar layout', THEME_SLUG); ?></label>
	  	<select name="thr[use_sidebar]" class="widefat">
	  	<?php foreach($sidebars_lay as $id => $name): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['use_sidebar']);?>/><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select>
	   <p class="description"><?php _e('Use sidebar?',THEME_SLUG); ?></p>
	 </div>

	  <?php if(!empty($sidebars)): ?>
	  <div class="form-field">
	  <label><?php _e('Standard sidebar', THEME_SLUG); ?></label>
	  	<select name="thr[sidebar]" class="widefat">
	  	<?php foreach($sidebars as $id => $name): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['sidebar']);?>><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select>
	  <p class="description"><?php _e('Choose standard sidebar to display',THEME_SLUG); ?></p>
	  </div>
	  <div class="form-field">
	  <label><?php _e('Sticky sidebar', THEME_SLUG); ?></label>
	  <select name="thr[sticky_sidebar]" class="widefat">
	  	<?php foreach($sidebars as $id => $name): ?>
	  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['sticky_sidebar']);?>><?php echo $name; ?></option>
	  	<?php endforeach; ?>
	  </select>
	   <p class="description"><?php _e('Choose sticky sidebar to display',THEME_SLUG); ?></p>
	   </div>
	  <?php endif; ?>
		
	<?php
	}
endif;

add_action( 'category_add_form_fields', 'thr_category_add_meta_fields', 10, 2 );

if(!function_exists('thr_category_edit_meta_fields')) :
	function thr_category_edit_meta_fields($term) {
	  $thr_meta = thr_get_category_meta($term->term_id);
	  $sidebars_lay = thr_get_sidebar_layouts(true);
	  $sidebars = thr_get_sidebars_list(true);
	  $layouts = thr_get_post_layouts(true);
	  $fa_layouts = thr_featured_area_layouts(true);
	  ?>
	  <tr class="form-field">
		<th scope="row" valign="top">
	  		<label><?php _e('Featured area layout', THEME_SLUG); ?></label>
	  	</th>
	  	<td>
		  	<select name="thr[fa_layout]" class="widefat">
		  	<?php foreach($fa_layouts as $id => $v): ?>
		  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['fa_layout']);?>><?php echo $v['title']; ?></option>
		  	<?php endforeach; ?>
		  	</select>
		    <p class="description"><?php _e('Choose which featured area layout to display for this category',THEME_SLUG); ?></p>
	   </td>
	 </tr>

	 <tr class="form-field">
		<th scope="row" valign="top">
	  		<label><?php _e('Posts layout', THEME_SLUG); ?></label>
	  	</th>
	  	<td>
		  	<select name="thr[layout]" class="widefat">
		  	<?php foreach($layouts as $id => $v): ?>
		  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['layout']);?>><?php echo $v['title']; ?></option>
		  	<?php endforeach; ?>
		  	</select>
		    <p class="description"><?php _e('Choose which posts layout to display for this category',THEME_SLUG); ?></p>
	   </td>
	 </tr>

	  <tr class="form-field">
		<th scope="row" valign="top">
	  		<label><?php _e('Sidebar layout', THEME_SLUG); ?></label>
	  	</th>
	  	<td>
		  	<select name="thr[use_sidebar]" class="widefat">
		  	<?php foreach($sidebars_lay as $id => $name): ?>
		  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['use_sidebar']);?>/><?php echo $name; ?></option>
		  	<?php endforeach; ?>
		 	</select>
		   	<p class="description"><?php _e('Use sidebar?',THEME_SLUG); ?></p>
	 	</td>
	  </tr>

	  <tr class="form-field">
		<th scope="row" valign="top">
	  		<label><?php _e('Standard sidebar', THEME_SLUG); ?></label>
	  	</th>
	  	<td>
			<select name="thr[sidebar]" class="widefat">
			<?php foreach($sidebars as $id => $name): ?>
				<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['sidebar']);?>><?php echo $name; ?></option>
			<?php endforeach; ?>
			</select>
			<p class="description"><?php _e('Choose standard sidebar to display',THEME_SLUG); ?></p>
	  	</td>
	  </tr>
	  <tr class="form-field">
		<th scope="row" valign="top">
	  		<label><?php _e('Sticky sidebar', THEME_SLUG); ?></label>
	  	</th>
	  	<td>
		  	<select name="thr[sticky_sidebar]" class="widefat">
		  	<?php foreach($sidebars as $id => $name): ?>
		  		<option value="<?php echo $id; ?>" <?php selected($id,$thr_meta['sticky_sidebar']);?>><?php echo $name; ?></option>
		  	<?php endforeach; ?>
		  	</select>
		    <p class="description"><?php _e('Choose sticky sidebar to display',THEME_SLUG); ?></p>
	   </td>
	 </tr>
	
	<?php
	}
endif;

add_action( 'category_edit_form_fields', 'thr_category_edit_meta_fields', 10, 2 );


if(!function_exists('thr_save_category_meta_fields')) :
	function thr_save_category_meta_fields( $term_id ) {
		
		//thr_log($_POST);
		if ( isset( $_POST['thr'] ) ) {

			$thr_meta = array();

		  	$thr_meta['use_sidebar'] = isset($_POST['thr']['use_sidebar']) ? $_POST['thr']['use_sidebar'] : 0;
		  	$thr_meta['sidebar'] = isset($_POST['thr']['sidebar']) ? $_POST['thr']['sidebar'] : 0;
		  	$thr_meta['sticky_sidebar'] = isset($_POST['thr']['sticky_sidebar']) ? $_POST['thr']['sticky_sidebar'] : 0;
		  	$thr_meta['layout'] = isset($_POST['thr']['layout']) ? $_POST['thr']['layout'] : 'inherit';
		  	$thr_meta['fa_layout'] = isset($_POST['thr']['fa_layout']) ? $_POST['thr']['fa_layout'] : 'inherit';

			update_option('_thr_category_'.$term_id, $thr_meta );
		}
	
	}
endif;

add_action( 'edited_category', 'thr_save_category_meta_fields', 10, 2 );  
add_action( 'create_category', 'thr_save_category_meta_fields', 10, 2 );
?>