<?php
/*-----------------------------------------------------------------------------------*/
/*	Include snippets to modify/add some features to this theme
/*-----------------------------------------------------------------------------------*/

/* Allow shortcodes in widgets */
add_filter( 'widget_text', 'do_shortcode' );

/* Add classes to body tag */
if ( !function_exists( 'thr_body_class' ) ):
	function thr_body_class( $classes ) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

		if ( $is_lynx ) $classes[] = 'lynx';
		elseif ( $is_gecko ) $classes[] = 'gecko';
		elseif ( $is_opera ) $classes[] = 'opera';
		elseif ( $is_NS4 ) $classes[] = 'ns4';
		elseif ( $is_safari ) $classes[] = 'safari';
		elseif ( $is_chrome ) $classes[] = 'chrome';
		elseif ( $is_IE ) $classes[] = 'ie';
		else $classes[] = 'unknown';

		if ( $is_iphone ) $classes[] = 'iphone';

		if ( ( $content_class = thr_get_option( 'main_content_style' ) ) != 'default' ) {
			$classes[] = $content_class;
		}

		if ( ( $sidebar_class = thr_get_option( 'sidebar_style' ) ) != 'default' ) {
			$classes[] = $sidebar_class;
		}

		return $classes;
	}
endif;

add_filter( 'body_class', 'thr_body_class' );

/* Backwards support for wp title tag ( if version < wp 4.1) */
if ( ! function_exists( '_wp_render_title_tag' ) ) :

	if ( ! function_exists( 'thr_render_title' ) ) :
		function thr_render_title() {
			echo '<title>';
			wp_title( '|', true, 'right' );
			echo '</title>';
		}
	endif;

add_action( 'wp_head', 'thr_render_title' );

/* Add wp_title filter */
if ( !function_exists( 'thr_wp_title' ) ):
	function thr_wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', THEME_SLUG ), max( $paged, $page ) );

		return $title;
	}
endif;

add_filter( 'wp_title', 'thr_wp_title', 10, 2 );

endif;

/* Initialize Theme Updater */
if ( !function_exists( 'thr_run_updater' ) ):
	function thr_run_updater() {

		$user = thr_get_option( 'theme_update_username' );
		$apikey = thr_get_option( 'theme_update_apikey' );
		if ( !empty( $user ) && !empty( $apikey ) ) {
			include_once 'update/class-pixelentity-theme-update.php';
			PixelentityThemeUpdate::init( $user, $apikey );
		}
	}
endif;

add_action( 'admin_init', 'thr_run_updater' );

/* Pre get posts - check if current archive template has posts in feature area to exclude those posts from main query */
if ( !function_exists( 'thr_pre_get_posts' ) ):
	function thr_pre_get_posts( $query ) {

		//Check for ppp value on specific archive layout
		if ( !is_admin() && $query->is_main_query() && $query->is_archive() ) {
			$current_layout = thr_get_posts_layout();
			$map = array(
				'layout-a' => 'lay_a',
				'layout-b' => 'lay_b',
				'layout-c' => 'lay_c',
				'layout-d' => 'lay_d'
			);
			if ( thr_get_option( $map[$current_layout].'_ppp' ) == 'custom' ) {
				$ppp_custom = absint( thr_get_option( $map[$current_layout].'_ppp_num' ) );
				$query->set( 'posts_per_page', $ppp_custom );
			}
		}

		//Exclude latest "x" posts if featured area is enabled on category, tag or author template
		if ( !is_admin() && $query->is_main_query() && ( $query->is_category() || $query->is_tag() || $query->is_author() ) ) {

			$offset = thr_get_current_fa_count();

			if ( $offset ) {
				//Next, determine how many posts per page you want (we'll use WordPress's settings)
				$ppp = isset( $ppp_custom ) ? $ppp_custom : get_option( 'posts_per_page' );

				//Next, detect and handle pagination...
				if ( $query->is_paged ) {

					//Manually determine page query offset (offset + current page (minus one) x posts per page)
					$page_offset = $offset + ( ( $query->query_vars['paged']-1 ) * $ppp );

					//Apply adjust page offset
					$query->set( 'offset', $page_offset );

				} else {

					//This is the first page. Just use the offset...
					$query->set( 'offset', $offset );

				}
			}

		}

	}
endif;

add_action( 'pre_get_posts', 'thr_pre_get_posts' );

/* Found posts - check if current archive template has posts in feature area to exclude those posts from main query */
if ( !function_exists( 'thr_adjust_offset_pagination' ) ):
	function thr_adjust_offset_pagination( $found_posts, $query ) {

		if ( !is_admin() && $query->is_main_query() && ( $query->is_category() || $query->is_tag() || $query->is_author() ) ) {

			$offset = thr_get_current_fa_count();

			if ( $offset ) {
				return $found_posts - $offset;
			} else {
				return $found_posts;
			}
		}

		return $found_posts;
	}
endif;

add_filter( 'found_posts', 'thr_adjust_offset_pagination', 1, 2 );


/* Add items dynamically to menu*/
if ( !function_exists( 'thr_extend_navigation' ) ):
	function thr_extend_navigation( $items, $args ) {
		if ( $args->theme_location == 'thr_main_navigation_menu' && thr_get_option( 'header_search' ) ) {
			$items .= '<li id="search_header_wrap"><a id="search_header" class="search_header" href="javascript:void(0)"><i class="fa fa-search"></i></a>';
			$items .= '<form class="search_header_form" action="'.esc_url( home_url( '/' ) ).'" method="get">
		<input name="s" class="search_input" size="20" type="text" value="'.__thr( 'search_form' ).'" onfocus="(this.value == \''.__thr( 'search_form' ).'\') && (this.value = \'\')" onblur="(this.value == \'\') && (this.value = \''.__thr( 'search_form' ).'\')" placeholder="'.__thr( 'search_form' ).'" />
		</form>';
			$items .= '</li>';
		}
		return $items;
	}
endif;

add_action( 'wp_nav_menu_items', 'thr_extend_navigation', 10, 2 );

/* Extend user default social profiles  */
if ( !function_exists( 'thr_user_social_profiles' ) ):
	function thr_user_social_profiles( $contactmethods ) {

		unset( $contactmethods['aim'] );
		unset( $contactmethods['yim'] );
		unset( $contactmethods['jabber'] );

		$social = thr_get_social();
		foreach ( $social as $soc_id => $soc_name ) {
			if ( $soc_id ) {
				$contactmethods[$soc_id] = $soc_name;
			}
		}
		return $contactmethods;
	}
endif;
add_filter( 'user_contactmethods', 'thr_user_social_profiles' );

/* Delete category custom meta from database on category deletion */
if ( !function_exists( 'thr_delete_category_meta' ) ):
	function thr_delete_category_meta( $term_id ) {
		delete_option( '_thr_category_'.$term_id );
	}
endif;

add_action( 'delete_category', 'thr_delete_category_meta' );

/* Set tag sizes in tag cloud widget */
if ( !function_exists( 'thr_set_tag_cloud_sizes' ) ):
	function thr_set_tag_cloud_sizes( $args ) {
		$args['smallest'] = 10;
		$args['largest'] = 20;
		return $args;
	}
endif;

add_filter( 'widget_tag_cloud_args', 'thr_set_tag_cloud_sizes' );

/* Change atts of wp gallery shortcode to get best sizes depends of column selection */
if ( !function_exists( 'thr_gallery_atts' ) ):
	function thr_gallery_atts( $out, $pairs, $atts ) {

		if ( !isset( $atts['columns'] ) ) {
			$atts['columns'] = 3;
		}

		if ( $atts['columns'] == 1 ) {
			$size = 'thr-layout-a';
			global $thr_sidebar_opts;
			if ( !$thr_sidebar_opts['use_sidebar'] ) {
				$size.='-nosid';
			}
		}  else if ( $atts['columns'] == 2 ) {
				$size = 'thr-fa-half';
			} else if ( $atts['columns'] == 3 ) {
				$size = 'thr-fa-third';
			} else if ( $atts['columns'] >= 4 && $atts['columns'] <= 6 ) {
				$size = 'thr-fa-quarter';
			} else {
			$size = 'thr-layout-d';
		}

		$out['columns'] = $atts['columns'];
		$out['size'] = $size;
		$out['link'] = 'file';

		return $out;

	}
endif;

add_filter( 'shortcode_atts_gallery', 'thr_gallery_atts', 10, 3 );

/* Change customize link to lead to theme options instead of live customizer */
if ( !function_exists( 'thr_change_customize_link' ) ):
	function thr_change_customize_link( $themes ) {
		if ( array_key_exists( 'throne', $themes ) ) {
			$themes['throne']['actions']['customize'] = admin_url( 'admin.php?page=thr_options' );
		}
		return $themes;
	}
endif;

add_filter( 'wp_prepare_themes_for_js', 'thr_change_customize_link' );

/* Get favicon options */
if ( !function_exists( 'thr_wp_head' ) ):
	function thr_wp_head() {

		//Add favicons
		if ( $favicon = thr_get_option_media( 'favicon' ) ) {
			echo '<link rel="shortcut icon" href="'.$favicon.'" type="image/x-icon" />';
		}

		if ( $apple_touch_icon = thr_get_option_media( 'apple_touch_icon' ) ) {
			echo '<link rel="apple-touch-icon" href="'.$apple_touch_icon.'" />';
		}

		if ( $metro_icon = thr_get_option_media( 'metro_icon' ) ) {
			echo '<meta name="msapplication-TileColor" content="#ffffff">';
			echo '<meta name="msapplication-TileImage" content="'.$metro_icon.'" />';
		}

		//Additional CSS (if user adds his custom css inside theme options)
		$additional_css = trim( preg_replace( '/\s+/', ' ', thr_get_option( 'additional_css' ) ) );
		if ( !empty( $additional_css ) ) {
			echo '<style type="text/css">'.$additional_css.'</style>';
		}

	}
endif;

add_action( 'wp_head', 'thr_wp_head', 99 );

/* For advanced use - add some CSS and JS into theme footer if specified in theme options */
if ( !function_exists( 'thr_wp_footer' ) ):
	function thr_wp_footer() {

		//Additional JS
		$additional_js = trim( preg_replace( '/\s+/', ' ', thr_get_option( 'additional_js' ) ) );
		if ( !empty( $additional_js ) ) {
			echo '<script type="text/javascript">
				/* <![CDATA[ */
					'.$additional_js.'
				/* ]]> */
				</script>';
		}

		//Google Analytics (tracking)
		if ( $ga = thr_get_option( 'ga' ) ) {
			echo $ga;
		}

	}
endif;

add_action( 'wp_footer', 'thr_wp_footer', 99 );


/* Show welcome message and quick tips after theme activation */
if ( !function_exists( 'thr_welcome_msg' ) ):
	function thr_welcome_msg() {
		if ( !get_option( 'thr_welcome_box_displayed' ) ) {
			update_option( 'thr_theme_version', THEME_VERSION );
			include_once THEME_DIR.'sections/welcome.php';
		}
	}
endif;

/* Show message box after theme update */
if ( !function_exists( 'thr_update_msg' ) ):
	function thr_update_msg() {
		if ( get_option( 'thr_welcome_box_displayed' ) ) {

			$prev_version = get_option( 'thr_theme_version' );

			$cur_version = THEME_VERSION;
			if ( $prev_version === false ) {$prev_version = '0.0.0';}
			if ( version_compare( $cur_version, $prev_version, '>' ) ) {
				include_once THEME_DIR.'sections/update-notify.php';
			}
		}
	}
endif;

/* Show admin notices */
if ( !function_exists( 'thr_check_installation' ) ):
	function thr_check_installation() {
		add_action( 'admin_notices', 'thr_welcome_msg', 1 );
		add_action( 'admin_notices', 'thr_update_msg', 1 );
	}
endif;

add_action( 'admin_init', 'thr_check_installation' );

/* Add span element to post count number in category widget */
if ( !function_exists( 'thr_add_span_cat_count' ) ):
	function thr_add_span_cat_count( $links ) {
		$links = str_replace( '</a> (', '<span class="count">', $links );
		$links = str_replace( ')', '</span></a>', $links );
		return $links;
	}
endif;
add_filter( 'wp_list_categories', 'thr_add_span_cat_count' );


/* Store registered sidebars so you can get them before wp_registered_sidebars is initialized to use them in theme options */
if ( !function_exists( 'thr_check_sidebars' ) ):
	function thr_check_sidebars() {
		global $wp_registered_sidebars;
		if ( !empty( $wp_registered_sidebars ) ) {
			update_option( 'thr_registered_sidebars', $wp_registered_sidebars );
		}
	}
endif;

add_action( 'admin_init', 'thr_check_sidebars' );

/* Function that outputs the contents of the dashboard widget */
if ( !function_exists( 'thr_dashboard_widget_cb' ) ):
	function thr_dashboard_widget_cb() {

		$hide = false;

		if ( $data = get_transient( 'thr_mksaw' ) ) {
			if ( $data != 'error' ) {
				echo $data;
			} else {
				$hide = true;
			}
		} else {
			$protocol = is_ssl() ? 'https://' : 'http://';
			$url = $protocol.'demo.mekshq.com/mksaw.php';
			$args = array( 'body' => array( 'key' => md5( 'meks' ), 'theme' => 'throne' ) );
			$response = wp_remote_post( $url, $args );
			if ( !is_wp_error( $response ) ) {
				$json = wp_remote_retrieve_body( $response );
				if ( !empty( $json ) ) {
					$json = ( json_decode( $json ) );
					if ( isset( $json->data ) ) {
						echo $json->data;
						set_transient( 'thr_mksaw', $json->data, 86400 );
					} else {
						set_transient( 'thr_mksaw', 'error', 86400 );
						$hide = true;
					}
				} else {
					set_transient( 'thr_mksaw', 'error', 86400 );
					$hide = true;
				}

			} else {
				set_transient( 'thr_mksaw', 'error', 86400 );
				$hide = true;
			}
		}

		if ( $hide ) {
			echo '<style>#thr_dashboard_widget {display:none;}</style>'; //hide widget if data is not returned properly
		}

	}
endif;

/* Add dashboard widget */
if ( !function_exists( 'thr_add_dashboard_widgets' ) ):
	function thr_add_dashboard_widgets() {
		add_meta_box( 'thr_dashboard_widget', 'Meks - WordPress Themes & Plugins', 'thr_dashboard_widget_cb', 'dashboard', 'side', 'high' );
	}
endif;

add_action( 'wp_dashboard_setup', 'thr_add_dashboard_widgets' );

/* Prevent redirect issue that may brake home page pagination caused by some plugins */
if ( !function_exists( 'thr_disable_redirect_canonical' ) ):
	function thr_disable_redirect_canonical( $redirect_url ) {
		if ( is_page_template( 'template-home.php' ) && is_paged() ) {
			$redirect_url = false;
		}
		return $redirect_url;
	}
endif;

add_filter( 'redirect_canonical', 'thr_disable_redirect_canonical' );


/* Add class to image inside content so we can trigger pop-up safely */
if ( !function_exists( 'thr_add_content_image_class' ) ):
	function thr_add_content_image_class( $content ) {
		if ( thr_get_option( 'lightbox_content_img' ) ) {
			global $post;
			$pattern ="/<a(.*?)><img(.*?)>/i";
			$replacement = '<a$1 class="thr-popup"><img$2>';
			$content = preg_replace( $pattern, $replacement, $content );
		}
		return $content;
	}
endif;

add_filter( 'the_content', 'thr_add_content_image_class' );

/* Unregister Entry Views widget */
if ( !function_exists( 'thr_unregister_widgets' ) ):
	function thr_unregister_widgets() {

		$widgets = array( 'EV_Widget_Entry_Views' );

		//Allow child themes or plugins to add/remove widgets they want to unregister
		$widgets = apply_filters( 'thr_modify_unregister_widgets', $widgets );

		if ( !empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				unregister_widget( $widget );
			}
		}

	}
endif;


add_action( 'widgets_init', 'thr_unregister_widgets', 99 );

/* Remove entry views support for other post types, we need post support only */
if ( !function_exists( 'thr_remove_entry_views_support' ) ):
	function thr_remove_entry_views_support() {

		$types = array( 'page', 'attachment', 'literature', 'portfolio_item', 'recipe', 'restaurant_item' );

		//Allow child themes or plugins to modify entry views support
		$widgets = apply_filters( 'thr_modify_entry_views_support', $types );

		if ( !empty( $types ) ) {
			foreach ( $types as $type ) {
				remove_post_type_support( $type, 'entry-views' );
			}
		}

	}
endif;

add_action( 'init', 'thr_remove_entry_views_support', 99 );

?>