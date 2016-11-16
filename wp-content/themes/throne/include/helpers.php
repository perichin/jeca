<?php
/*-----------------------------------------------------------------------------------*/
/*	Helpers and utils functions for theme use
/*-----------------------------------------------------------------------------------*/


/* 	Debug (log) function */
if ( !function_exists( 'thr_log' ) ):
	function thr_log( $mixed ) {

		if ( is_array( $mixed ) ) {
			$mixed = print_r( $mixed, 1 );
		} else if ( is_object( $mixed ) ) {
				ob_start();
				var_dump( $mixed );
				$mixed = ob_get_clean();
			}

		$handle = fopen( THEME_DIR . 'log', 'a' );
		fwrite( $handle, $mixed . PHP_EOL );
		fclose( $handle );
	}
endif;

/* 	Get theme option function */
if ( !function_exists( 'thr_get_option' ) ):
	function thr_get_option( $option ) {
		global $thr_settings;

		if ( empty( $thr_settings ) ) {
			$thr_settings = get_option( 'thr_settings' );
		}

		if ( isset( $thr_settings[$option] ) ) {
			return $thr_settings[$option];
		} else {
			return false;
		}
	}
endif;

/* Extend the_category() function to show any post/custom_post_type taxonomies */
if ( !function_exists( 'thr_the_taxonomy' ) ):
	function thr_the_taxonomy( $taxonomy, $separator = '' ) {
		global $post;
		$terms = wp_get_object_terms( $post->ID, $taxonomy );
		$term_output = array();
		foreach ( $terms as $term ) {
			$link = get_term_link( (int)$term->term_id, $term->taxonomy );
			$term_output[] = '<a href="'.$link.'">'.$term->name.'</a>';
		}
		echo implode( $separator, $term_output );
	}
endif;

/* Get first image src from post content */
if ( !function_exists( 'thr_first_image' ) ):
	function thr_first_image() {
		global $post;
		$first_img = '';
		$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
		if ( isset( $matches[1][0] ) ) {
			$first_img = $matches[1][0];
			return $first_img;
		}

		return false;
	}
endif;

/* Get image id by url */
if ( !function_exists( 'thr_get_image_id_by_url' ) ):
	function thr_get_image_id_by_url( $image_url ) {
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );

		if ( isset( $attachment[0] ) ) {
			return $attachment[0];
		}

		return false;
	}
endif;

/* Display featured image, and more :) */
if ( !function_exists( 'thr_featured_image' ) ):
	function thr_featured_image( $size = 'large', $use_sidebar = true, $post_id = false ) {

		if ( !$use_sidebar && $size ) {
			$size .='-nosid';
		}

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( has_post_thumbnail( $post_id ) ) {
			return get_the_post_thumbnail( $post_id, $size );

		} else if ( !strstr( $size, 'thr-layout-a' ) && ( $placeholder = thr_get_option_media( 'default_fimg' ) ) ) {

				global $placeholder_img, $placeholder_imgs;

				if ( empty( $placeholder_img ) ) {
					$img_id = thr_get_image_id_by_url( $placeholder );
				} else {
					$img_id = $placeholder_img;
				}

				if ( !empty( $img_id ) ) {
					if ( !isset( $placeholder_imgs[$size] ) ) {
						$def_img = wp_get_attachment_image( $img_id, $size );
					} else {
						$def_img = $placeholder_imgs[$size];
					}

					if ( !empty( $def_img ) ) {
						$placeholder_imgs[$size] = $def_img;
						return $def_img;
					}
				}

				return '<img src="'.$placeholder.'" />';
			}

		return false;
	}
endif;

if ( !function_exists( 'thr_read_time' ) ) :


	function thr_read_time( $text ) {
		$words = str_word_count( strip_tags( $text ) );
		if ( !empty( $words ) ) {
			$time_in_minutes = ceil( $words / 200 );
			return $time_in_minutes;
		}
		return false;
	}

endif;


if ( !function_exists( 'thr_get_post_order_opts' ) ) :

	/**
	 * Retrieves post ordering options
	 * 
	 * This is a simple helper function that's being used to retrieve post ordering options.
	 * 
	 * @return array $options - Array of post order options
	 * @since 1.5
	 */

	function thr_get_post_order_opts() {

		$options = array(
			'date' => __( 'Date', THEME_SLUG ),
			'comment_count' => __( 'Number of comments', THEME_SLUG ),
			'views' => __( 'Number of views', THEME_SLUG ),
			'rand' => __( 'Random', THEME_SLUG )

		);

		//Allow child themes or plugins to change these options
		$options = apply_filters( 'thr_modify_post_order_opts', $options );

		return $options;
	}
endif;


if ( !function_exists( 'thr_get_time_diff_opts' ) ) :

	/**
	 * Retrieves time range options
	 * 
	 * This is a simple helper function that's being used to retrieve time range options.
	 * 
	 * @return array $options - Array of post order options
	 * @since 1.6
	 */
	function thr_get_time_diff_opts() {

		$options = array(
			'-1 day' => __( '1 Day', THEME_SLUG ),
			'-3 days' => __( '3 Days', THEME_SLUG ),
			'-1 week' => __( '1 Week', THEME_SLUG ),
			'-1 month' => __( '1 Month', THEME_SLUG ),
			'-3 months' => __( '3 Months', THEME_SLUG ),
			'-6 months' => __( '6 Months', THEME_SLUG ),
			'-1 year' => __( '1 Year', THEME_SLUG ),
			'0' => __( 'All time', THEME_SLUG )
		);

		//Allow child themes or plugins to change these options
		$options = apply_filters( 'thr_modify_time_diff_opts', $options );

		return $options;
	}
endif;

if ( !function_exists( 'thr_get_related_posts' ) ):

	/**
	 * Retrieves related posts on single post template
	 * 
	 * This is a simple helper function that's being used to retrieve related posts based on theme options
	 * 
	 * @return WP_Query $related_query - Fully Built WP_Query object that has related posts
	 * @since 1.6
	 */

	function thr_get_related_posts( $post_id = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$args['post_type'] = 'post';

		//Exclude current post form query
		$args['post__not_in'] = array( $post_id );

		//If previuos next posts active exclude them too
		if ( thr_get_option( 'show_prev_next' ) ) {
			$in_same_cat = thr_get_option( 'prev_next_cat' ) ? true : false;
			$prev = get_previous_post( $in_same_cat );

			if ( !empty( $prev ) ) {
				$args['post__not_in'][] = $prev->ID;
			}
			$next = get_next_post( $in_same_cat );
			if ( !empty( $next ) ) {
				$args['post__not_in'][] = $next->ID;
			}
		}

		$num_posts = absint( thr_get_option( 'related_limit' ) );
		if ( $num_posts > 100 ) {
			$num_posts = 100;
		}
		$args['posts_per_page'] = $num_posts;
		$args['orderby'] = thr_get_option( 'related_order' );

		if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = ev_get_meta_key();
		}

		if ( $time_diff = thr_get_option( 'related_time' ) ) {
			$args['date_query'] = array( 'after' => date( 'Y-m-d', thr_calculate_time_diff( $time_diff ) ) );
		}

		if ( $type = thr_get_option( 'related_type' ) ) {
			switch ( $type ) {

			case 'cat':
				$cats = get_the_category( $post_id );
				$cat_args = array();
				if ( !empty( $cats ) ) {
					foreach ( $cats as $k => $cat ) {
						$cat_args[] = $cat->term_id;
					}
				}
				$args['category__in'] = $cat_args;
				break;

			case 'tag':
				$tags = get_the_tags( $post_id );
				$tag_args = array();
				if ( !empty( $tags ) ) {
					foreach ( $tags as $tag ) {
						$tag_args[] = $tag->term_id;
					}
				}
				$args['tag__in'] = $tag_args;
				break;

			case 'cat_and_tag':
				$cats = get_the_category( $post_id );
				$cat_args = array();
				if ( !empty( $cats ) ) {
					foreach ( $cats as $k => $cat ) {
						$cat_args[] = $cat->term_id;
					}
				}
				$tags = get_the_tags( $post_id );
				$tag_args = array();
				if ( !empty( $tags ) ) {
					foreach ( $tags as $tag ) {
						$tag_args[] = $tag->term_id;
					}
				}
				$args['tax_query'] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $cat_args,
					),
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'id',
						'terms'    => $tag_args,
					)
				);
				break;

			case 'cat_or_tag':
				$cats = get_the_category( $post_id );
				$cat_args = array();
				if ( !empty( $cats ) ) {
					foreach ( $cats as $k => $cat ) {
						$cat_args[] = $cat->term_id;
					}
				}
				$tags = get_the_tags( $post_id );
				$tag_args = array();
				if ( !empty( $tags ) ) {
					foreach ( $tags as $tag ) {
						$tag_args[] = $tag->term_id;
					}
				}
				$args['tax_query'] = array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $cat_args,
					),
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'id',
						'terms'    => $tag_args,
					)
				);
				break;

			case 'author':
				global $post;
				$author_id = isset( $post->post_author ) ? $post->post_author : 0;
				$args['author'] = $author_id;
				break;

			case 'default':
				break;
			}
		}

		$related_query = new WP_Query( $args );

		return $related_query;
	}
endif;

/* Check wheter to display date in standard or "time ago" format */
if ( !function_exists( 'thr_get_date' ) ):
	function thr_get_date() {

		if ( thr_get_option( 'time_ago' ) ) {

			$limits = array(
				'hour' => 3600,
				'day' => 86400,
				'week' => 604800,
				'month' => 2592000,
				'three_months' => 7776000,
				'six_months' => 15552000,
				'year' => 31104000,
				'0' => 0
			);

			$ago_limit = thr_get_option( 'time_ago_limit' );

			if ( array_key_exists( $ago_limit, $limits ) ) {

				if ( ( current_time( 'timestamp' ) - get_the_time( 'U' ) <= $limits[$ago_limit] ) || empty( $ago_limit ) ) {
					if ( thr_get_option( 'ago_before' ) ) {
						return __thr( 'ago' ).' '.human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
					} else {
						return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ).' '.__thr( 'ago' );
					}
				} else {
					return get_the_date();
				}
			} else {
				return get_the_date();
			}
		} else {
			return get_the_date();
		}
	}
endif;

/* Display different comments icon for one or more comments :) */
if ( !function_exists( 'thr_get_comments_icon' ) ):
	function thr_get_comments_icon() {
		if ( get_comments_number() > 1 ) {
			return 'bubbles';
		} else {
			return 'bubble';
		}
	}
endif;

/* Get post listing layouts */
if ( !function_exists( 'thr_get_post_layouts' ) ):
	function thr_get_post_layouts( $inherit = false ) {
		$layouts = array(
			'layout-a' => array( 'title' => __( 'Layout A', THEME_SLUG ), 'img' => IMG_URI . 'layout_a.png' ),
			'layout-b' => array( 'title' => __( 'Layout B', THEME_SLUG ), 'img' => IMG_URI . 'layout_b.png' ),
			'layout-c' => array( 'title' => __( 'Layout C', THEME_SLUG ), 'img' => IMG_URI . 'layout_c.png' ),
			'layout-d' => array( 'title' => __( 'Layout D', THEME_SLUG ), 'img' => IMG_URI . 'layout_d.png' )
		);

		if ( $inherit ) {
			$layouts = array_merge( array( 'inherit' => array( 'title' => __( 'Inherit', THEME_SLUG ) ) ), $layouts );
		}

		return $layouts;
	}
endif;

/* Get featured area layouts */
if ( !function_exists( 'thr_featured_area_layouts' ) ):
	function thr_featured_area_layouts( $inherit = false ) {
		$layouts = array(
			'2_0' => array( 'title' => __( '2 posts inline', THEME_SLUG ), 'img' => IMG_URI . '2_posts_inline.png' ),
			'3_0' => array( 'title' => __( '3 posts inline', THEME_SLUG ), 'img' => IMG_URI . '3_posts_inline.png' ),
			'4_0' => array( 'title' => __( '4 posts inline', THEME_SLUG ), 'img' => IMG_URI . '4_posts_inline.png' ),
			'2_2' => array( 'title' => __( '4 posts <span>(2 top + 2 bottom)</span>', THEME_SLUG ),  'img' => IMG_URI . '2_2_posts.png' ),
			'2_3' => array( 'title' => __( '5 posts <span>(2 top + 3 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '2_3_posts.png' ),
			'2_4' => array( 'title' => __( '6 posts <span>(2 top + 4 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '2_4_posts.png' ),
			'3_2' => array( 'title' => __( '5 posts <span>(3 top + 2 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '3_2_posts.png' ),
			'3_3' => array( 'title' => __( '6 posts <span>(3 top + 3 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '3_3_posts.png' ),
			'3_4' => array( 'title' => __( '7 posts <span>(3 top + 4 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '3_4_posts.png' ),
			'4_2' => array( 'title' => __( '6 posts <span>(4 top + 2 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '4_2_posts.png' ),
			'4_3' => array( 'title' => __( '7 posts <span>(4 top + 3 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '4_3_posts.png' ),
			'4_4' => array( 'title' => __( '8 posts <span>(4 top + 4 bottom)</span>', THEME_SLUG ), 'img' => IMG_URI . '4_4_posts.png' )
		);

		if ( $inherit ) {
			$layouts = array_merge( array( 'inherit' => array( 'title' => __( 'Inherit', THEME_SLUG ) ), '0' => array( 'title' => __( 'None', THEME_SLUG ) ) ), $layouts );
		}

		return $layouts;
	}
endif;

/* Get sidebar layouts */
if ( !function_exists( 'thr_get_sidebar_layouts' ) ):
	function thr_get_sidebar_layouts( $inherit = false ) {
		$sidebars = array();
		if ( $inherit ) {
			$sidebars['inherit'] = __( 'Inherit', THEME_SLUG );
		}
		$sidebars['0'] = __( 'No sidebar (full width content)', THEME_SLUG );
		$sidebars['left'] = __( 'Left sidebar', THEME_SLUG );
		$sidebars['right'] = __( 'Right sidebar', THEME_SLUG );
		return $sidebars;
	}
endif;

/* Get all sidebars */
if ( !function_exists( 'thr_get_sidebars_list' ) ):
	function thr_get_sidebars_list( $inherit = false ) {

		$sidebars = array();

		if ( $inherit ) {
			$sidebars['inherit'] = __( 'Inherit', THEME_SLUG );
		}

		$sidebars['0'] = __( 'None', THEME_SLUG );

		global $wp_registered_sidebars;

		if ( !empty( $wp_registered_sidebars ) ) {

			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[$sidebar['id']] = $sidebar['name'];
			}

		} else {

			//Get sidebars from wp_options if global var is not loaded yet
			$fallback_sidebars = get_option( 'thr_registered_sidebars' );
			if ( !empty( $fallback_sidebars ) ) {
				foreach ( $fallback_sidebars as $sidebar ) {
					if ( !array_key_exists( $sidebar['id'], $sidebars ) ) {
						$sidebars[$sidebar['id']] = $sidebar['name'];
					}
				}
			}

			//Check for theme additional sidebars
			$custom_sidebars = thr_get_option( 'add_sidebars' );

			if ( empty( $custom_sidebars ) ) {
				$settings = get_option( 'thr_settings' );
				$custom_sidebars = isset( $settings['add_sidebars'] ) ? $settings['add_sidebars'] : false;
			}

			if ( $custom_sidebars ) {
				for ( $i = 1; $i <= $custom_sidebars; $i++ ) {
					if ( !array_key_exists( 'thr_custom_sidebar_'.$i, $sidebars ) ) {
						$sidebars['thr_custom_sidebar_'.$i] = __( 'Additional Sidebar', THEME_SLUG ).' '.$i;
					}
				}
			}
		}

		return $sidebars;
	}
endif;

/* Get current sidebar options */
if ( !function_exists( 'thr_get_current_sidebar' ) ):
	function thr_get_current_sidebar() {

		$use_sidebar = false;
		$sidebar = '';
		$sticky_sidebar = '';
		$thr_template = thr_detect_template();

		if ( in_array( $thr_template, array( 'search', 'tag', 'author', 'other_archives' ) ) ) {

			$use_sidebar = thr_get_option( $thr_template.'_use_sidebar' );
			if ( $use_sidebar ) {
				$sidebar = thr_get_option( $thr_template.'_sidebar' );
				$sticky_sidebar = thr_get_option( $thr_template.'_sticky_sidebar' );
			}

		} else if ( $thr_template == 'category' ) {
				$obj = get_queried_object();
				if ( isset( $obj->term_id ) ) {
					$meta = thr_get_category_meta( $obj->term_id );
				}

				if ( $meta['use_sidebar'] ) {
					$use_sidebar = ( $meta['use_sidebar'] == 'inherit' ) ? thr_get_option( $thr_template.'_use_sidebar' ) : $meta['use_sidebar'];
					if ( $use_sidebar ) {
						$sidebar = ( $meta['sidebar'] == 'inherit' ) ?  thr_get_option( $thr_template.'_sidebar' ) : $meta['sidebar'];
						$sticky_sidebar = ( $meta['sidebar'] == 'inherit' ) ?  thr_get_option( $thr_template.'_sticky_sidebar' ) : $meta['sticky_sidebar'];
					}
				}

			} else if ( $thr_template == 'single' ) {

				$meta = thr_get_post_meta( get_the_ID() );
				$use_sidebar = ( $meta['use_sidebar'] == 'inherit' ) ? thr_get_option( $thr_template.'_use_sidebar' ) : $meta['use_sidebar'];
				if ( $use_sidebar ) {
					$sidebar = ( $meta['sidebar'] == 'inherit' ) ?  thr_get_option( $thr_template.'_sidebar' ) : $meta['sidebar'];
					$sticky_sidebar = ( $meta['sidebar'] == 'inherit' ) ?  thr_get_option( $thr_template.'_sticky_sidebar' ) : $meta['sticky_sidebar'];
				}

			} else if ( in_array( $thr_template, array( 'home_page', 'page', 'posts_page' ) ) ) {
				if ( $thr_template == 'posts_page' ) {
					$meta = thr_get_page_meta( get_option( 'page_for_posts' ) );
				} else {
					$meta = thr_get_page_meta( get_the_ID() );
				}


				$use_sidebar = ( $meta['use_sidebar'] == 'inherit' ) ? thr_get_option( 'page_use_sidebar' ) : $meta['use_sidebar'];
				if ( $use_sidebar ) {
					$sidebar = ( $meta['sidebar'] == 'inherit' ) ?  thr_get_option( 'page_sidebar' ) : $meta['sidebar'];
					$sticky_sidebar = ( $meta['sidebar'] == 'inherit' ) ?  thr_get_option( 'page_sticky_sidebar' ) : $meta['sticky_sidebar'];
				}

			}

		$args = array(
			'use_sidebar' => $use_sidebar,
			'sidebar' => $sidebar,
			'sticky_sidebar' => $sticky_sidebar
		);

		return $args;
	}
endif;

/* Get current featured area options */
if ( !function_exists( 'thr_get_current_fa' ) ):
	function thr_get_current_fa() {

		$output['query'] = false;
		$output['display'] = false;
		$args = array();

		$thr_template = thr_detect_template();

		if ( in_array( $thr_template, array( 'tag', 'author', 'category' ) ) ) {

			if ( $thr_template == 'category' ) {
				$obj = get_queried_object();
				$meta = thr_get_category_meta( $obj->term_id );
				if ( $meta['fa_layout'] == 'inherit' ) {

					if ( $use_fa = thr_get_option( $thr_template.'_featured_area' ) ) {
						$featured_area_layout = thr_get_option( $thr_template.'_fa_layout' );
					} else {
						$use_fa = false;
					}
				} else {
					$featured_area_layout = $meta['fa_layout'];
					if ( !$featured_area_layout ) {
						$use_fa = false;
					} else {
						$use_fa = true;
					}
				}

			} else {
				$use_fa = thr_get_option( $thr_template.'_featured_area' );
			}


			if ( $use_fa ) {

				$output['display'] = true;

				$featured_area_layout = thr_get_option( $thr_template.'_fa_layout' );

				$obj = get_queried_object();

				if ( $thr_template == 'category' ) {
					$args['cat'] = $obj->term_id;
					$meta = thr_get_category_meta( $obj->term_id );
					$featured_area_layout = ( $meta['fa_layout'] == 'inherit' ) ? $featured_area_layout : $meta['fa_layout'];
				} else if ( $thr_template == 'tag' ) {
						$args['tag_id'] = $obj->term_id;
					} else if ( $thr_template == 'author' ) {
						$args['author'] = $obj->ID;
					}
			}

		} else if ( $thr_template == 'home_page' ) {

				$paginated = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );

				$use_fa = thr_get_option( 'home_featured_area' );

				if ( $use_fa ) {

					$output['display'] = true;

					$featured_area_layout = thr_get_option( 'home_fa_layout' );
					$orderby = thr_get_option( 'home_fa_posts_order' );

					if ( $orderby != 'manual' ) {

						//Orderby
						$args['orderby'] =  $orderby;

						//Check if is ordered by views
						if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
							$args['orderby'] = 'meta_value_num';
							$args['meta_key'] = ev_get_meta_key();
						}

						//Cat
						$cats = thr_get_option( 'home_fa_posts_cat' );
						if ( !empty( $cats ) ) {
							$args['cat'] = implode( ",", $cats );
						}

						//Tag
						$tags = thr_get_option( 'home_fa_posts_tag' );
						if ( !empty( $tags ) ) {
							$args['tag__in'] =  $tags;
						}

					} else {
						if ( $manual_posts = thr_get_option( 'home_fa_posts_manual_force' ) ) {
							$manual_posts = explode( ",", $manual_posts );
						} else {
							$manual_posts = thr_get_option( 'home_fa_posts_manual' );
						}
						$args['orderby'] =  'post__in';
						$args['post__in'] =  $manual_posts;
						$post_types = array( 'post', 'page' );
					}

				}

			} //endif

		if ( $output['display'] ) {

			$option = explode( '_', $featured_area_layout );
			$top = $option[0];
			$bottom = $option[1];
			$posts_per_page = $top + $bottom;
			$args['post_type'] = isset( $post_types ) ? $post_types : 'post';
			$args['posts_per_page'] = absint( $posts_per_page );
			$args['ignore_sticky_posts'] = 1;

			$output['query'] = new WP_Query( $args );
			$output['top'] = $top;
			$output['bottom'] = $bottom;

			if ( $thr_template == 'home_page' && isset( $output['query']->posts ) && !empty( $output['query']->posts ) ) {
				global $thr_home_exclude_ids;
				$thr_home_exclude_ids = array();
				foreach ( $output['query']->posts as $p ) {
					$thr_home_exclude_ids[] = $p->ID;
				}

				if ( thr_get_option( 'home_pag_fa_hide' ) && $paginated >= 2 ) {
					$output['display'] = false; //take the posts for home page fa to exclude them from main list, but do not display featured area
				}
			}
		}

		return $output;
	}
endif;

/* Get current featured area posts count */
if ( !function_exists( 'thr_get_current_fa_count' ) ):
	function thr_get_current_fa_count() {

		$thr_template = thr_detect_template();

		if ( in_array( $thr_template, array( 'tag', 'author', 'category' ) ) ) {

			if ( $thr_template == 'category' ) {
				$obj = get_queried_object();
				$meta = thr_get_category_meta( $obj->term_id );
				if ( $meta['fa_layout'] == 'inherit' ) {

					if ( $use_fa = thr_get_option( $thr_template.'_featured_area' ) ) {
						$featured_area_layout = thr_get_option( $thr_template.'_fa_layout' );
					} else {
						$use_fa = false;
					}

				} else {

					$featured_area_layout = $meta['fa_layout'];
					if ( !$featured_area_layout ) {
						$use_fa = false;
					} else {
						$use_fa = true;
					}
				}

			} else {
				$use_fa = thr_get_option( $thr_template.'_featured_area' );
			}



			if ( $use_fa ) {

				if ( $thr_template != 'category' ) {
					$featured_area_layout = thr_get_option( $thr_template.'_fa_layout' );
				}

				$option = explode( '_', $featured_area_layout );
				$top = $option[0];
				$bottom = $option[1];
				$offset = $top + $bottom;
				return absint( $offset );
			}
		}

		return false;
	}
endif;

/* Get current posts layout  */
if ( !function_exists( 'thr_get_posts_layout' ) ):
	function thr_get_posts_layout() {

		$layout = 'layout-a'; //default
		$thr_template = thr_detect_template();

		if ( in_array( $thr_template, array( 'search', 'tag', 'author', 'other_archives', 'posts_page' ) ) ) {

			$layout = thr_get_option( $thr_template.'_layout' );

		} else if ( $thr_template == 'category' ) {

				$obj = get_queried_object();
				if ( isset( $obj->term_id ) ) {
					$meta = thr_get_category_meta( $obj->term_id );
					$layout = ( $meta['layout'] == 'inherit' ) ? thr_get_option( $thr_template.'_layout' ) : $meta['layout'];
				}

			}

		return $layout;
	}
endif;

/* Get single post layout */
if ( !function_exists( 'thr_get_single_layout' ) ):
	function thr_get_single_layout() {
		$layout = thr_get_post_meta( get_the_ID(), 'layout' );

		if ( $layout == 'inherit' ) {
			return thr_get_option( 'single_layout' );
		}

		return $layout;
	}
endif;

/* Detect WordPress template */
if ( !function_exists( 'thr_detect_template' ) ):
	function thr_detect_template() {
		$template = '';
		if ( is_single() ) {
			$template = 'single';
		} else if ( is_page_template( 'template-home.php' ) ) {
				$template = 'home_page';
			} else if ( is_page() ) {
				$template = 'page';
			} else if ( is_category() ) {
				$template = 'category';
			} else if ( is_tag() ) {
				$template = 'tag';
			} else if ( is_search() ) {
				$template = 'search';
			} else if ( is_author() ) {
				$template = 'author';
			} else if ( is_home() && ( $posts_page = get_option( 'page_for_posts' ) ) && !is_page_template( 'template-home.php' ) ) {
				$template = 'posts_page';
			} else {
			$template = 'other_archives';
		}
		return $template;
	}
endif;

/* Get post format icon */
if ( !function_exists( 'thr_post_format_icon' ) ):
	function thr_post_format_icon() {
		$format = get_post_format();

		$icons = array(
			'video' => 'camcorder',
			'audio' => 'music-tone-alt',
			'image' => 'camera',
			'gallery' => 'picture'
		);

		if ( $format && array_key_exists( $format, $icons ) ) {
			return $icons[$format];
		}

		return false;
	}
endif;



/* Include simple pagination */
if ( !function_exists( 'thr_pagination' ) ):
	function thr_pagination( $prev = '&lsaquo;', $next = '&rsaquo;' ) {
		global $wp_query, $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
		$pagination = array(
			'base' => @add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => $wp_query->max_num_pages,
			'current' => $current,
			'prev_text' => $prev,
			'next_text' => $next,
			'type' => 'plain'
		);
		if ( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

		if ( !empty( $wp_query->query_vars['s'] ) )
			$pagination['add_args'] = array( 's' => str_replace( ' ', '+', get_query_var( 's' ) ) );

		$links = paginate_links( $pagination );

		if ( $links ) {
			echo '<div id="thr_pagination">'.$links.'</div>';
		}
	}
endif;

/* Limit words in string */
if ( !function_exists( 'thr_trim_words' ) ):
	function thr_trim_words( $text, $limit = false, $more = '...' ) {

		$words = explode( ' ', $text );
		if ( count( $words ) > $limit ) {
			$result = implode( ' ', array_slice( $words, 0, $limit ) );
			$text = rtrim( $result, ".,-?!" );
			$text .= $more;
		}

		return $text;
	}
endif;

/* Custom function to limit post content words */
if ( !function_exists( 'thr_get_excerpt' ) ):
	function thr_get_excerpt( $layout = 'layout-a' ) {

		$map = array(
			'layout-a' => 'lay_a',
			'layout-b' => 'lay_b',
			'layout-c' => 'lay_c',
			'layout-d' => 'lay_d',
			'fa' => 'lay_fa'
		);

		if ( !array_key_exists( $layout, $map ) ) {
			return '';
		}

		if ( ( $layout != 'layout-a' || ( $layout == 'layout-a' ) && thr_get_option( 'lay_a_content_type' ) == 'excerpt' )  && has_excerpt() ) {
			$content =  get_the_excerpt();
		} else {
			//$content = apply_filters('the_content',get_the_content(''));
			$text = get_the_content( '' );
			$text = strip_shortcodes( $text );
			$text = apply_filters( 'the_content', $text );
			$content = str_replace( ']]>', ']]&gt;', $text );
		}

		//print_r($content);

		if ( !empty( $content ) ) {
			$limit = thr_get_option( $map[$layout].'_excerpt_limit' );
			$more = thr_get_option( $map[$layout].'_excerpt_more' );
			$content = wp_strip_all_tags( $content );
			$content = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $content );
			$excerpt = thr_trim_chars( $content, $limit, $more );
			return $excerpt;
		}

		return '';

	}
endif;


/* Custom function to get meta data for specific layout */
if ( !function_exists( 'thr_get_meta_data' ) ):
	function thr_get_meta_data( $layout = 'layout-a', $force_meta = false ) {

		if ( !$force_meta ) {

			$map = array(
				'layout-a' => 'lay_a',
				'layout-b' => 'lay_b',
				'layout-c' => 'lay_c',
				'layout-d' => 'lay_d',
				'single' => 'single',
				'fa' => 'lay_fa'
			);
			//Layouts theme options
			$layout_metas = array_filter( thr_get_option( $map[$layout].'_meta' ) );

		} else {
			//From widget or anywhere else
			$layout_metas = array( $force_meta => '1' );
		}

		$output = '';

		if ( !empty( $layout_metas ) ) {

			foreach ( $layout_metas as $mkey => $active ) {


				$meta = '';

				switch ( $mkey ) {

				case 'date':
					$meta = '<i class="icon-clock"></i><span class="updated">'.thr_get_date().'</span>';
					break;
				case 'author':
					$meta = '<i class="icon-user"></i><span class="vcard author"><span class="fn"><a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a></span></span>';
					break;

				case 'views':
					global $wp_locale;
					$thousands_sep = isset( $wp_locale->number_format['thousands_sep'] ) ? $wp_locale->number_format['thousands_sep'] : ',';
					$meta = function_exists( 'ev_get_post_view_count' ) ?  number_format( absint( str_replace( $thousands_sep, '', ev_get_post_view_count( get_the_ID() ) ) ), 0, '', ',' )  . ' '.__thr( 'views' ) : '';
					if(!empty($meta)){
						$meta = '<i class="icon-eye"></i>'.$meta;
					}
					break;

				case 'rtime':
					$meta = thr_read_time( get_post_field( 'post_content', get_the_ID() ) );
					if ( !empty( $meta ) ) {
						$meta = '<i class="icon-hourglass"></i>'.$meta.' '.__thr( 'min_read' );
					}
					break;

				case 'comments':
					if ( comments_open() || get_comments_number() ) {
						ob_start();
						comments_popup_link( __thr( 'no_comments' ), __thr( 'one_comment' ), __thr( 'multiple_comments' ) );
						$meta = '<i class="icon-'.thr_get_comments_icon().'"></i>'.ob_get_contents();
						ob_end_clean();
					} else {
						$meta = '';
					}
					break;

				case 'categories':
					$c = get_the_category_list( ', ' );
					if ( !empty( $c ) ) {
						$meta = '<i class="icon-note"></i>'.$c;
					}

					break;

				default:
					break;
				}

				if ( !empty( $meta ) ) {
					$output .= '<div class="meta-item '.$mkey.'">'.$meta.'</div>';
				}
			}
		}


		return $output;

	}
endif;

/* Custom function to limit post title chars for specific layout */
if ( !function_exists( 'thr_get_title' ) ):
	function thr_get_title( $layout = 'layout-b' ) {

		$map = array(
			'layout-b' => 'lay_b',
			'layout-c' => 'lay_c',
			'layout-d' => 'lay_d',
			'fa' => 'lay_fa'
		);

		if ( !array_key_exists( $layout, $map ) ) {
			return get_the_title();
		}


		$title_limit = thr_get_option( $map[$layout].'_title_limit' );


		if ( !empty( $title_limit ) ) {
			$output = thr_trim_chars( strip_tags( get_the_title() ), $title_limit, thr_get_option( $map[$layout].'_title_more' ) );
		} else {
			$output = get_the_title();
		}


		return $output;

	}
endif;

/* Trim chars of string */
if ( !function_exists( 'thr_trim_chars' ) ):
	function thr_trim_chars( $string, $limit, $more = '...' ) {

		if ( strlen( $string ) > $limit ) {
			$last_space = strrpos( substr( $string, 0, $limit ), ' ' );
			$string = substr( $string, 0, $last_space );
			$string = rtrim( $string, ".,-?!" );
			$string.= $more;
		}

		return $string;
	}
endif;


/* Convert hexdec color string to rgba */
if ( !function_exists( 'thr_hex2rgba' ) ):
	function thr_hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) )
			return $default;

		//Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) { $opacity = 1.0; }
			$output = 'rgba('.implode( ",", $rgb ).','.$opacity.')';
		} else {
			$output = 'rgb('.implode( ",", $rgb ).')';
		}

		//Return rgb(a) color string
		return $output;
	}
endif;

/* Get array of social options  */
if ( !function_exists( 'thr_get_social' ) ) :
	function thr_get_social( $existing = false ) {
		$social = array(
			'0' => 'None',
			'apple' => 'Apple',
			'behance' => 'Behance',
			'delicious' => 'Delicious',
			'deviantart' => 'DeviantArt',
			'digg' => 'Digg',
			'dribbble' => 'Dribbble',
			'facebook' => 'Facebook',
			'flickr' => 'Flickr',
			'github' => 'Github',
			'google' => 'GooglePlus',
			'instagram' => 'Instagram',
			'linkedin' => 'LinkedIN',
			'pinterest' => 'Pinterest',
			'reddit' => 'ReddIT',
			'rss' => 'Rss',
			'skype' => 'Skype',
			'stumbleupon' => 'StumbleUpon',
			'soundcloud' => 'SoundCloud',
			'spotify' => 'Spotify',
			'tumblr' => 'Tumblr',
			'twitter' => 'Twitter',
			'vimeo' => 'Vimeo',
			'vine' => 'Vine',
			'wordpress' => 'WordPress',
			'xing' => 'Xing' ,
			'yahoo' => 'Yahoo',
			'youtube' => 'Youtube'
		);

		if ( $existing ) {
			$new_social = array();
			foreach ( $social as $key => $soc ) {
				if ( $key && thr_get_option( 'soc_'.$key.'_url' ) ) {
					$new_social[$key] = $soc;
				}
			}
			$social = $new_social;
		}

		return $social;
	}
endif;


/* Get Theme Translated String */
if ( !function_exists( '__thr' ) ):
	function __thr( $string_key ) {
		if ( ( $translated_string = thr_get_option( 'tr_'.$string_key ) ) && thr_get_option( 'enable_translate' ) ) {
			if ( $translated_string == '-1' ) {
				return "";
			}
			return $translated_string;
		} else {
			$translate = thr_get_translate_options();
			return $translate[$string_key]['translated'];
		}
	}
endif;

/* Get All Translation Strings */
if ( !function_exists( 'thr_get_translate_options' ) ):
	function thr_get_translate_options() {
		global $thr_translate;
		require_once 'translate.php';
		$translate = apply_filters( 'thr_modify_translate_options', $thr_translate );
		return $translate;
	}
endif;

/* Compress CSS Code  */
if ( !function_exists( 'thr_compress_css_code' ) ) :
	function thr_compress_css_code( $code ) {

		// Remove Comments
		$code = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code );

		// Remove tabs, spaces, newlines, etc.
		$code = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $code );

		return $code;
	}
endif;

/* Get page meta with default values */
if ( !function_exists( 'thr_get_page_meta' ) ):
	function thr_get_page_meta( $post_id, $field = false ) {
		$defaults = array(
			'use_sidebar' => 'inherit',
			'sidebar' => 'thr_default_sidebar',
			'sticky_sidebar' => 'thr_default_sticky_sidebar'
		);

		if ( !$post_id ) {
			$post_id = get_the_ID();
		}


		$meta = get_post_meta( $post_id, '_thr_meta', true );
		$meta = wp_parse_args( (array) $meta, $defaults );


		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;

/* Get post meta with default values */
if ( !function_exists( 'thr_get_post_meta' ) ):
	function thr_get_post_meta( $post_id, $field = false ) {
		$defaults = array(
			'use_sidebar' => 'inherit',
			'sidebar' => 'thr_default_sidebar',
			'sticky_sidebar' => 'thr_default_sticky_sidebar',
			'layout' => 'inherit'
		);

		$meta = get_post_meta( $post_id, '_thr_meta', true );
		$meta = wp_parse_args( (array) $meta, $defaults );

		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;

/* Get category meta with default values */
if ( !function_exists( 'thr_get_category_meta' ) ):
	function thr_get_category_meta( $cat_id = false, $field = false ) {
		$defaults = array(
			'use_sidebar' => 'inherit',
			'sidebar' => 'inherit',
			'sticky_sidebar' => 'inherit',
			'layout' => 'inherit',
			'fa_layout' => 'inherit'
		);
		if ( $cat_id ) {
			$meta = get_option( '_thr_category_'.$cat_id );
			$meta = wp_parse_args( (array) $meta, $defaults );
		} else {
			$meta = $defaults;
		}

		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


if ( !function_exists( 'thr_widget_orderby' ) ):

	/**
	 * Function to draw orderby select box inside throne posts widget form
	 *
	 * This function is deprecated and kept for compatibility purposes only.
	 * It will be removed in Throne 1.6, so update your child theme overrides accordingly.
	 *
	 * @param WP_Widget $widget_instance
	 * @param string  $orderby
	 * @param array   $opts
	 * @since 1.0
	 * @deprecated 1.5
	 * @return void
	 */
	function thr_widget_orderby( $widget_instance = false, $orderby = false, $opts = array() ) {
		$orders = array(
			'date' => __( 'Published date', THEME_SLUG ),
			'menu_order' => __( 'Menu order', THEME_SLUG ),
			'rand' => __( 'Random', THEME_SLUG ),
			'views' => __( 'Number of views', THEME_SLUG ),
			'comment_count' => __( 'Popularity (Number of comments)', THEME_SLUG )
		);

		if ( is_array( $opts ) && !empty( $opts ) ) {
			$new_orders = array();
			foreach ( $opts as $opt ) {
				if ( array_key_exists( $opt, $orders ) ) {
					$new_orders[$opt] = $orders[$opt];
				}
			}
			if ( !empty( $new_orders ) ) {
				$orders = $new_orders;
			}
		}

		if ( !empty( $widget_instance ) ) { ?>
				<label for="<?php echo $widget_instance->get_field_id( 'orderby' ); ?>"><?php _e( 'Order by:', THEME_SLUG ); ?></label>
				<select id="<?php echo $widget_instance->get_field_id( 'orderby' ); ?>" name="<?php echo $widget_instance->get_field_name( 'orderby' ); ?>" class="widefat">
					<?php foreach ( $orders as $key => $order ) { ?>
						<option value="<?php echo $key; ?>" <?php selected( $orderby, $key );?>><?php echo $order; ?></option>
					<?php } ?>
				</select>
		<?php }
	}

endif;



if ( !function_exists( 'thr_widget_tax' ) ):

	/**
	 * Function to draw taxonomy selectbox inside throne posts widget form
	 *
	 * This function is deprecated and kept for compatibility purposes only.
	 * It will be removed in Throne 1.6, so update your child theme overrides accordingly.
	 *
	 * @param WP_Widget $widget_instance
	 * @param WP_Term $taxonomy
	 * @param integer $selected_taxonomy
	 * @since 1.0
	 * @deprecated 1.5
	 * @return void
	 */
	function thr_widget_tax( $widget_instance, $taxonomy, $selected_taxonomy = false ) {
		if ( !empty( $widget_instance ) && !empty( $taxonomy ) ) {
			$categories = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );
?>
				<label for="<?php echo $widget_instance->get_field_id( 'category' ); ?>"><?php _e( 'Choose from:', THEME_SLUG ); ?></label>
				<select id="<?php echo $widget_instance->get_field_id( 'category' ); ?>" name="<?php echo $widget_instance->get_field_name( 'category' ); ?>" class="widefat">
					<option value="0" <?php selected( $selected_taxonomy, 0 );?>><?php _e( 'All categories', THEME_SLUG ); ?></option>
					<?php foreach ( $categories as $category ) { ?>
						<option value="<?php echo $category->term_id; ?>" <?php selected( $category->term_id, $selected_taxonomy );?>><?php echo $category->name; ?></option>
					<?php } ?>
				</select>
		<?php }
	}

endif;

/* Get image sizes */
if ( !function_exists( 'thr_get_image_sizes' ) ):
	function thr_get_image_sizes() {
		$sizes = array(
			'thr-fa-half' => array( 'title' => 'Featured area half', 'w' => 534, 'h' => 267, 'crop' => true ),
			'thr-fa-third' => array( 'title' => 'Featured area third', 'w' => 356, 'h' => 267, 'crop' => true ),
			'thr-fa-quarter' => array( 'title' => 'Featured area quarter', 'w' => 267, 'h' => 267, 'crop' => true ),
			'thr-layout-a' => array( 'title' => 'Layout A', 'w' => 730, 'h' => 9999, 'crop' => false ),
			'thr-layout-a-nosid' => array( 'title' => 'Layout A (no sidebar)', 'w' => 1070, 'h' => 9999, 'crop' => false ),
			'thr-layout-b' => array( 'title' => 'Layout B', 'w' => 267, 'h' => 267, 'crop' => true ),
			'thr-layout-c' => array( 'title' => 'Layout C', 'w' => 350, 'h' => 185, 'crop' => true ),
			'thr-layout-c-nosid' => array( 'title' => 'Layout C (no sidebar)', 'w' => 514, 'h' => 272, 'crop' => true ),
			'thr-layout-d' => array( 'title' => 'Layout D', 'w' => 100, 'h' => 100, 'crop' => true )
		);
		return $sizes;
	}
endif;


/* Get image option url */
if ( !function_exists( 'thr_get_option_media' ) ):
	function thr_get_option_media( $option ) {
		$media = thr_get_option( $option );
		if ( isset( $media['url'] ) && !empty( $media['url'] ) ) {
			return $media['url'];
		}
		return false;
	}
endif;

/* Generate font links */
if ( !function_exists( 'thr_generate_font_links' ) ):
	function thr_generate_font_links() {

		$output = array();
		$fonts = array();
		$fonts[] = thr_get_option( 'main_font' );
		$fonts[] = thr_get_option( 'h_font' );
		$fonts[] = thr_get_option( 'nav_font' );
		$unique = array(); //do not add same font links
		$native = thr_get_native_fonts();
		$protocol = is_ssl() ? 'https://' : 'http://';

		foreach ( $fonts as $font ) {
			if ( !in_array( $font['font-family'], $native ) ) {
				$temp = array();
				if ( isset( $font['font-style'] ) ) {
					$temp['font-style'] = $font['font-style'];
				}
				if ( isset( $font['subsets'] ) ) {
					$temp['subsets'] = $font['subsets'];
				}
				if ( isset( $font['font-weight'] ) ) {
					$temp['font-weight'] = $font['font-weight'];
				}
				$unique[$font['font-family']][] = $temp;
			}
		}

		foreach ( $unique as $family => $items ) {

			$link = $protocol.'fonts.googleapis.com/css?family='.str_replace( ' ', '%20', $family ); //valid

			$weight = array( '400' );
			$subsets = array( 'latin' );

			foreach ( $items as $item ) {

				//Check weight and style
				if ( isset( $item['font-weight'] ) && !empty( $item['font-weight'] ) ) {
					$temp = $item['font-weight'];
					if ( isset( $item['font-style'] ) && empty( $item['font-style'] ) ) {
						$temp .= $item['font-style'];
					}

					if ( !in_array( $temp, $weight ) ) {
						$weight[] = $temp;
					}
				}

				//Check subsets
				if ( isset( $item['subsets'] ) && !empty( $item['subsets'] ) ) {
					if ( !in_array( $item['subsets'], $subsets ) ) {
						$subsets[] = $item['subsets'];
					}
				}
			}

			$link .= ':'.implode( ",", $weight );
			$link .= '&subset='.implode( ",", $subsets );

			$output[] = str_replace( '&', '&amp;', $link ); //valid
		}

		return $output;
	}
endif;

/* Generate dynamic CSS */
if ( !function_exists( 'thr_generate_dynamic_css' ) ):
	function thr_generate_dynamic_css() {
		ob_start();
		get_template_part( 'css/dynamic-css' );
		$output = ob_get_contents();
		ob_end_clean();
		return thr_compress_css_code( $output );
	}
endif;

/* Check if featured area  is enabled on home page to exclude those posts from main loop */
if ( !function_exists( 'thr_exclude_home_page_posts' ) ):
	function thr_exclude_home_page_posts() {

		if ( thr_get_option( 'home_featured_area' ) && thr_get_option( 'home_do_not_duplicate' ) ) {

			global $thr_home_exclude_ids;

			if ( !empty( $thr_home_exclude_ids ) ) {
				return $thr_home_exclude_ids;
			}
		}

		return false;
	}
endif;

/* Get list of native fonts */
if ( !function_exists( 'thr_get_native_fonts' ) ):
	function thr_get_native_fonts() {

		$fonts = array(
			"Arial, Helvetica, sans-serif",
			"'Arial Black', Gadget, sans-serif",
			"'Bookman Old Style', serif",
			"'Comic Sans MS', cursive",
			"Courier, monospace",
			"Garamond, serif",
			"Georgia, serif",
			"Impact, Charcoal, sans-serif",
			"'Lucida Console', Monaco, monospace",
			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
			"'MS Sans Serif', Geneva, sans-serif",
			"'MS Serif', 'New York', sans-serif",
			"'Palatino Linotype', 'Book Antiqua', Palatino, serif",
			"Tahoma,Geneva, sans-serif",
			"'Times New Roman', Times,serif",
			"'Trebuchet MS', Helvetica, sans-serif",
			"Verdana, Geneva, sans-serif"
		);

		return $fonts;
	}
endif;

/* Get home page posts */
if ( !function_exists( 'thr_get_home_page_posts' ) ):
	function thr_get_home_page_posts() {

		$args = array( 'post_type'=>'post' );

		//Check if we are on paginated home page
		if ( is_front_page() ) {
			$args['paged'] = get_query_var( 'page' );
			global $paged;
			$paged = $args['paged'];
		} else {
			$args['paged'] = get_query_var( 'paged' );
		}

		//Exclude featured area posts from main archive
		$home_post_ids = thr_exclude_home_page_posts();

		if ( !empty( $home_post_ids ) ) {
			$args['post__not_in'] = $home_post_ids;
		}

		$orderby = thr_get_option( 'home_posts_order' );

		if ( $orderby != 'manual' ) {

			//Orderby
			$args['orderby'] =  $orderby;

			//Check if is ordered by views
			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();
			}

			//Cat
			$cats = thr_get_option( 'home_posts_cat' );
			if ( !empty( $cats ) ) {
				$args['cat'] = implode( ",", $cats );
			}

			//Tag
			$tags = thr_get_option( 'home_posts_tag' );
			if ( !empty( $tags ) ) {
				$args['tag__in'] =  $tags;
			}

			//PPP
			$current_layout = thr_get_option( 'home_page_layout' );

			$map = array(
				'layout-a' => 'lay_a',
				'layout-b' => 'lay_b',
				'layout-c' => 'lay_c',
				'layout-d' => 'lay_d'
			);

			if ( thr_get_option( $map[$current_layout].'_ppp' ) == 'custom' ) {
				$ppp_custom = absint( thr_get_option( $map[$current_layout].'_ppp_num' ) );
				$args['posts_per_page']  = $ppp_custom;
			}

		} else {

			if ( $manual_posts = thr_get_option( 'home_posts_manual_force' ) ) {
				$manual_posts = explode( ",", $manual_posts );
			} else {
				$manual_posts = thr_get_option( 'home_posts_manual' );
			}

			$args['orderby'] =  'post__in';
			$args['post__in'] =  $manual_posts;
		}

		//Get posts for home page
		$query = new WP_Query( $args );

		return $query;
	}
endif;

/* Get settings to pass to main JS file */
if ( !function_exists( 'thr_get_js_settings' ) ):
	function thr_get_js_settings() {

		$js_settings = array();

		$js_settings['use_lightbox'] = thr_get_option( 'lightbox' ) ? true : false;
		$js_settings['use_lightbox_content'] = thr_get_option( 'lightbox_content_img' ) ? true : false;
		$js_settings['sticky_header'] = thr_get_option( 'sticky_header' ) ? true : false;
		$js_settings['sticky_header_offset'] = absint( thr_get_option( 'sticky_header_offset' ) );
		$js_settings['logo_retina'] = thr_get_option_media( 'logo_retina' );
		$js_settings['sticky_header_logo'] = thr_get_option_media( 'sticky_header_logo' );
		$js_settings['sticky_header_logo_retina'] = thr_get_option_media( 'sticky_header_logo_retina' );

		return $js_settings;
	}
endif;

/* Check if content has WordPress "more" tag */
if ( !function_exists( 'thr_has_more_tag' ) ):
	function thr_has_more_tag() {
		global $post;
		return strpos( $post->post_content, '<!--more-->' );
	}
endif;

/* Get update notification */
if ( !function_exists( 'thr_get_update_notification' ) ):
	function thr_get_update_notification() {
		$current = get_site_transient( 'update_themes' );
		$message_html = '';
		if ( isset( $current->response['throne'] ) ) {
			$message_html = '<span class="update-message">New update available!</span>
				<span class="update-actions">Version '.$current->response['throne']['new_version'].': <a href="http://demo.mekshq.com/throne/documentation#changelog" target="blank">See what\'s new</a><a href="'.admin_url( 'update-core.php' ).'">Update</a></span>';
		}

		return $message_html;
	}
endif;

/* Calculate time difference based on timestring */
if ( !function_exists( 'thr_calculate_time_diff' ) ) :
	function thr_calculate_time_diff( $timestring ) {

		$now = current_time( 'timestamp' );

		switch ( $timestring ) {
		case '-1 day' : $time = $now - DAY_IN_SECONDS; break;
		case '-3 days' : $time = $now - ( 3 * DAY_IN_SECONDS ); break;
		case '-1 week' : $time = $now - WEEK_IN_SECONDS; break;
		case '-1 month' : $time = $now - ( YEAR_IN_SECONDS / 12 ); break;
		case '-3 months' : $time = $now - ( 3 * YEAR_IN_SECONDS / 12 ); break;
		case '-6 months' : $time = $now - ( 6 * YEAR_IN_SECONDS / 12 ); break;
		case '-1 year' : $time = $now - ( YEAR_IN_SECONDS ); break;
		default : $time = $now;
		}

		return $time;
	}
endif;

?>