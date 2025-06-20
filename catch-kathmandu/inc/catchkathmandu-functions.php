<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */


/**
 * Enqueue scripts and styles
 */
function catchkathmandu_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	//Getting Ready to load data from Theme Options Panel
	global $post, $wp_query;

   	$options = catchkathmandu_get_options();

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	/**
	 * Loads up main stylesheet.
	 */
	wp_enqueue_style( 'catchkathmandu-style', get_stylesheet_uri(), null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/style.css' ) ) );

	// Theme block stylesheet.
	wp_enqueue_style( 'catchkathmandu-block-style', get_theme_file_uri( 'css/blocks.css' ), array( 'catchkathmandu-style' ), $theme_version );

	/**
	 * Add Genericons font, used in the main stylesheet.
	 */
	wp_enqueue_style( 'genericons', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/genericons/genericons.css', false, '3.4.1' );

	/**
	 * Loads up Color Scheme
	 */
	$color_scheme = $options['color_scheme'];
	if ( 'dark' == $color_scheme ) {
		wp_enqueue_style( 'dark', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/dark.css', array(), null );
	}
	elseif ( 'lightblack' == $color_scheme ) {
		wp_enqueue_style( 'lightblack', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/lightblack.css', array(), null );
	}

	/**
	 * Loads up Responsive stylesheet and Menu JS
	 */
	if ( empty ( $options['disable_responsive'] ) ) {
		wp_enqueue_style( 'catchkathmandu-responsive', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/responsive.css', false, $theme_version );

		wp_enqueue_script( 'jquery-fitvids', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchkathmandu.fitvids.min.js', array( 'jquery' ), $theme_version, true );

		wp_enqueue_script('catchkathmandu-menu-nav', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchkathmandu-menu.min.js', array( 'jquery' ), $theme_version, true );

		wp_localize_script( 'catchkathmandu-menu-nav', 'catchKathmanduOptions', array(
			'screenReaderText' => array(
				'expand'   => esc_html__( 'expand child menu', 'catch-kathmandu' ),
				'collapse' => esc_html__( 'collapse child menu', 'catch-kathmandu' ),
			),
		) );
	}

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	wp_enqueue_script( 'catchkathmandu-navigation', get_template_directory_uri() . '/js/navigation.min.js', array( 'jquery' ), '20150601', true );

	/**
	 * Register JQuery circle all and JQuery set up as dependent on Jquery-cycle
	 */
	wp_register_script( 'jquery-cycle', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/jquery.cycle.all.min.js', array( 'jquery' ), $theme_version, true );

	/**
	 * Loads up catchkathmandu-slider and jquery-cycle set up as dependent on catchkathmandu-slider
	 */
	$enableslider = $options['enable_slider'];
	if ( ( 'enable-slider-allpage' == $enableslider  ) || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'enable-slider-homepage' == $enableslider  ) ) {
		wp_enqueue_script( 'catchkathmandu-slider', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchkathmandu-slider.js', array( 'jquery-cycle' ), $theme_version, true );
	}

	/**
	 * Loads up Scroll Up script
	 */
	if ( empty( $options['disable_scrollup'] ) ) {
		wp_enqueue_script( 'catchkathmandu-scrollup', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchkathmandu-scrollup.min.js', array( 'jquery' ), '20072014', true  );
	}

	/**
	 * Browser Specific Enqueue Script
	 */
	// Load the html5 shiv.
	wp_enqueue_script( 'catchkathmandu-html5', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/html5.min.js', array(), '3.7.3' );
	wp_script_add_data( 'catchkathmandu-html5', 'conditional', 'lt IE 9' );

	// Load Selectivizr
	wp_enqueue_script( 'jquery-selectivizr', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/selectivizr.min.js', array( 'jquery' ), '20130114', false );
	wp_script_add_data( 'jquery-selectivizr', 'conditional', 'lt IE 9' );

	// Load IE CSS
	wp_enqueue_style( 'catchkathmandu-iecss', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/ie.css' );
	wp_style_add_data( 'catchkathmandu-iecss', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'catchkathmandu_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 */
function catchkathmandu_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'catchkathmandu-block-editor-style', get_theme_file_uri( 'css/editor-blocks.css' ) );
}
add_action( 'enqueue_block_editor_assets', 'catchkathmandu_block_editor_styles' );

/**
 * Responsive Layout
 *
 * @get the data value of responsive layout from theme options
 * @display responsive meta tag
 * @action wp_head
 */
function catchkathmandu_responsive() {
	//delete_transient('catchkathmandu_responsive');

	if ( !$catchkathmandu_responsive = get_transient( 'catchkathmandu_responsive' ) ) {
	$options = catchkathmandu_get_options();

		if ( $options['disable_responsive'] == '0' ) {
			$catchkathmandu_responsive = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		}
		else {
			$catchkathmandu_responsive = '<!-- Disable Responsive -->';
		}
		set_transient( 'catchkathmandu_responsive', $catchkathmandu_responsive, 86940 );
	}
	echo $catchkathmandu_responsive;
} // catchkathmandu_responsive
add_filter( 'wp_head', 'catchkathmandu_responsive', 1 );


if ( ! function_exists( 'catchkathmandu_featured_image' ) ) :
/**
 * Template for Featured Header Image from theme options
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_featured_image(), and that function will be used instead.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_featured_image() {
	//delete_transient( 'catchkathmandu_featured_image' );

	// Getting Data from Theme Options Panel
	$options = catchkathmandu_get_options();
	$defaults = catchkathmandu_get_defaults();

	$enableheaderimage = $options['enable_featured_header_image'];
	$catchkathmandu_featured_image = '';

	// Check function exists for WordPress version >= 4.5
	if ( function_exists( 'has_custom_logo' ) ) {
		$header_image = get_header_image();

		if ( ! empty( $header_image ) ){
			$catchkathmandu_featured_image = '<div id="header-image">';

			// Header Image Link and Target
			if ( !empty( $options['featured_header_image_url'] ) ) {
				//support for qtranslate custom link
				if ( function_exists( 'qtrans_convertURL' ) ) {
					$link = qtrans_convertURL($options['featured_header_image_url']);
				}
				else {
					$link = esc_url( $options['featured_header_image_url'] );
				}
				//Checking Link Target
				if ( !empty( $options['featured_header_image_base'] ) )  {
					$target = '_blank';
				}
				else {
					$target = '_self';
				}
			}
			else {
				$link = '';
				$target = '';
			}

			// Header Image Title/Alt
			if ( !empty( $options['featured_header_image_alt'] ) ) {
				$title = esc_attr( $options['featured_header_image_alt'] );
			}
			else {
				$title = '';
			}

			// Header Image Link
			if ( !empty( $options['featured_header_image_url'] ) ) :
				$catchkathmandu_featured_image .= '<a title="' . esc_attr( $title ) . '" href="' . esc_url( $options['featured_header_image_url'] ) .'" target="' . $base . '"><img id="main-feat-img" class="wp-post-image" alt="' . esc_attr( $title ) . '" src="' . esc_url( $header_image ) . ' " /></a>';
			else:
				// if empty featured_header_image on theme options, display default
				$catchkathmandu_featured_image .= '<img id="main-feat-img" class="wp-post-image" alt="' . esc_attr( $title ) . '" src="' . esc_url( $header_image ) . '" />';
			endif;

			$catchkathmandu_featured_image .= '</div><!-- #header-image -->';
		}
	}
	elseif ( !empty( $options['featured_header_image'] ) ) {

		$catchkathmandu_featured_image = '<div id="header-image">';

		// Header Image Link and Target
		if ( !empty( $options['featured_header_image_url'] ) ) {
			//support for qtranslate custom link
			if ( function_exists( 'qtrans_convertURL' ) ) {
				$link = qtrans_convertURL($options['featured_header_image_url']);
			}
			else {
				$link = esc_url( $options['featured_header_image_url'] );
			}
			//Checking Link Target
			if ( !empty( $options['featured_header_image_base'] ) )  {
				$target = '_blank';
			}
			else {
				$target = '_self';
			}
		}
		else {
			$link = '';
			$target = '';
		}

		// Header Image Title/Alt
		if ( !empty( $options['featured_header_image_alt'] ) ) {
			$title = esc_attr( $options['featured_header_image_alt'] );
		}
		else {
			$title = '';
		}

		// Header Image
		if ( !empty( $options['featured_header_image'] ) ) :
			$feat_image = '<img class="wp-post-image" src="'.esc_url( $options['featured_header_image'] ).'" />';
		else:
			// if empty featured_header_image on theme options, display default
			$feat_image = '<img class="wp-post-image" src="'.esc_url( $defaults[ 'featured_header_image' ] ).'" />';
		endif;

		$catchkathmandu_featured_image = '<div id="header-featured-image">';
			// Header Image Link
			if ( !empty( $options['featured_header_image_url'] ) ) :
				$catchkathmandu_featured_image .= '<a title="' . esc_attr( $title ) . '" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '"><img id="main-feat-img" class="wp-post-image" alt="' . esc_attr( $title ) . '" src="'.esc_url( $options['featured_header_image'] ).'" /></a>';
			else:
				// if empty featured_header_image on theme options, display default
				$catchkathmandu_featured_image .= '<img id="main-feat-img" class="wp-post-image" alt="' . esc_attr( $title ) . '" src="'.esc_url( $options['featured_header_image'] ).'" />';
			endif;
		$catchkathmandu_featured_image .= '</div><!-- #header-featured-image -->';
	}

	echo $catchkathmandu_featured_image;

} // catchkathmandu_featured_image
endif;


if ( ! function_exists( 'catchkathmandu_featured_page_post_image' ) ) :
/**
 * Template for Featured Header Image from Post and Page
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_featured_imaage_pagepost(), and that function will be used instead.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_featured_page_post_image() {

	global $post, $wp_query;
   	$options  = catchkathmandu_get_options();
	$featured_image = $options['page_featured_image'];


	if ( has_post_thumbnail() ) {

		echo '<div id="header-featured-image">';

			if ( !empty( $options['featured_header_image_url'] ) ) {
				// Header Image Link Target
				if ( !empty( $options['featured_header_image_base'] ) ) :
					$base = '_blank';
				else:
					$base = '_self';
				endif;

				// Header Image Title/Alt
				if ( !empty( $options['featured_header_image_alt'] ) ) :
					$title = esc_attr( $options['featured_header_image_alt'] );
				else:
					$title = '';
				endif;

				$linkopen = '<a title="' . esc_attr( $title ) . '" href="'.$options['featured_header_image_url'] .'" target="'.$base.'">';
				$linkclose = '</a>';
			}
			else {
				$linkopen = '';
				$linkclose = '';
			}

			echo $linkopen;
				if ( 'featured' == $featured_image  ) {
					echo get_the_post_thumbnail($post->ID, 'featured', array('id' => 'main-feat-img'));
				}
				elseif ( 'slider' == $featured_image  ) {
					echo get_the_post_thumbnail($post->ID, 'slider', array('id' => 'main-feat-img'));
				}
				else {
					echo get_the_post_thumbnail($post->ID, 'full', array('id' => 'main-feat-img'));
				}
			echo $linkclose;

		echo '</div><!-- #header-featured-image -->';

	}
	else {
		catchkathmandu_featured_image();
	}

} // catchkathmandu_featured_page_post_image
endif;


if ( ! function_exists( 'catchkathmandu_featured_overall_image' ) ) :
/**
 * Template for Featured Header Image from theme options
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_featured_pagepost_image(), and that function will be used instead.
 *
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_featured_overall_image() {

	global $post, $wp_query;
   	$options  = catchkathmandu_get_options();
	$enableheaderimage =  $options['enable_featured_header_image'];

	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Check Enable/Disable header image in Page/Post Meta box
	if ( is_page() || is_single() ) {
		//Individual Page/Post Image Setting
		$individual_featured_image = get_post_meta( $post->ID, 'catchkathmandu-header-image', true );

		if ( 'disable' == $individual_featured_image  || ( 'default' == $individual_featured_image  && 'disable' == $enableheaderimage  ) ) {
			echo '<!-- Page/Post Disable Header Image -->';
			return;
		}
		elseif ( 'enable' == $individual_featured_image  && 'disable' == $enableheaderimage  ) {
			catchkathmandu_featured_page_post_image();
		}
	}

	// Check Homepage
	if ( 'homepage' == $enableheaderimage  ) {
		if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
			catchkathmandu_featured_image();
		}
	}
	// Check Excluding Homepage
	if ( 'excludehome' == $enableheaderimage  ) {
		if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
			return false;
		}
		else {
			catchkathmandu_featured_image();
		}
	}
	// Check Entire Site
	elseif ( 'allpage' == $enableheaderimage  ) {
		catchkathmandu_featured_image();
	}
	// Check Entire Site (Post/Page)
	elseif ( 'postpage' == $enableheaderimage  ) {
		if ( is_page() || is_single() ) {
			catchkathmandu_featured_page_post_image();
		}
		else {
			catchkathmandu_featured_image();
		}
	}
	// Check Page/Post
	elseif ( 'pagespostes' == $enableheaderimage  ) {
		if ( is_page() || is_single() ) {
			catchkathmandu_featured_page_post_image();
		}
	}
	else {
		echo '<!-- Disable Header Image -->';
	}

} // catchkathmandu_featured_overall_image
endif;
add_action( 'catchkathmandu_after_hgroup_wrap', 'catchkathmandu_featured_overall_image', 10 );


if ( ! function_exists( 'catchkathmandu_content_image' ) ) :
/**
 * Template for Featured Image in Content
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_content_image(), and that function will be used instead.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_content_image() {
	global $post, $wp_query;

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( $post) {
 		if ( is_attachment() ) {
			$parent = $post->post_parent;
			$individual_featured_image = get_post_meta( $parent,'catchkathmandu-featured-image', true );
		} else {
			$individual_featured_image = get_post_meta( $page_id,'catchkathmandu-featured-image', true );
		}
	}

	if ( empty( $individual_featured_image ) || ( !is_page() && !is_single() ) ) {
		$individual_featured_image='default';
	}

	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();

	$featured_image = $options['featured_image'];

	if ( ( 'disable' == $individual_featured_image  || '' == get_the_post_thumbnail() || ( $individual_featured_image=='default' && 'disable' == $featured_image ) ) ) {
		return false;
	}
	else { ?>
		<figure class="featured-image">
            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'catch-kathmandu' ), the_title_attribute( 'echo=0' ) ) ); ?>">
                <?php
				if ( ( is_front_page() && 'featured' == $featured_image  ) ||  'featured' == $individual_featured_image  || ( $individual_featured_image=='default' && 'featured' == $featured_image  ) ) {
                     the_post_thumbnail( 'featured' );
                }
				elseif ( ( is_front_page() && 'slider' == $featured_image  ) || 'slider' == $individual_featured_image  || ( $individual_featured_image=='default' && 'slider' == $featured_image  ) ) {
					the_post_thumbnail( 'slider' );
				}
				else {
					the_post_thumbnail( 'full' );
				} ?>
			</a>
        </figure>
   	<?php
	}
}
endif; //catchkathmandu_content_image


/**
 * Hooks the Custom Inline CSS to head section
 *
 * @since Catch Kathmandu 1.0
 * @remove when WordPress version 5.0 is released
 */
function catchkathmandu_inline_css() {
	//delete_transient( 'catchkathmandu_inline_css' );
	/**
	 * Bail if WP version >=4.7 as we have migrated this option to core
	 */
	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		return;
	}

	if ( ( !$output = get_transient( 'catchkathmandu_inline_css' ) ) ) {
		// Getting data from Theme Options
		$options  = catchkathmandu_get_options();

		echo '<!-- refreshing cache -->' . "\n";
		if ( !empty( $options['custom_css'] ) ) {

			$output	.= '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
	        $output 	.= '<style type="text/css" media="screen">' . "\n";
			$output .=  $options['custom_css'] . "\n";
			$output 	.= '</style>' . "\n";

		}

		set_transient( 'catchkathmandu_inline_css', $output, 86940 );
	}
	echo $output;
}
add_action('wp_head', 'catchkathmandu_inline_css');


/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 * @uses filter excerpt_length
 */
function catchkathmandu_excerpt_length( $length ) {
	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();

	return $options['excerpt_length'];
}
add_filter( 'excerpt_length', 'catchkathmandu_excerpt_length' );


/**
 * Returns a "Continue Reading" link for excerpts
 */
function catchkathmandu_continue_reading() {
	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();

	$more_tag_text = $options['more_tag_text'];
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' .  $more_tag_text . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with catchkathmandu_continue_reading().
 *
 */
function catchkathmandu_excerpt_more( $more ) {
	return catchkathmandu_continue_reading();
}
add_filter( 'excerpt_more', 'catchkathmandu_excerpt_more' );


/**
 * Adds Continue Reading link to post excerpts.
 *
 * function tied to the get_the_excerpt filter hook.
 */
function catchkathmandu_custom_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= catchkathmandu_continue_reading();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'catchkathmandu_custom_excerpt' );


/**
 * Replacing Continue Reading link to the_content more.
 *
 * function tied to the the_content_more_link filter hook.
 */
function catchkathmandu_more_link( $more_link, $more_link_text ) {
	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();

	$more_tag_text = $options['more_tag_text'];

	return str_replace( $more_link_text, $more_tag_text, $more_link );
}
add_filter( 'the_content_more_link', 'catchkathmandu_more_link', 10, 2 );


/**
 * Redirect WordPress Feeds To FeedBurner
 */
function catchkathmandu_rss_redirect() {
	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();

    if ($options['feed_url']) {
		$url = 'Location: '.$options['feed_url'];
		if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT']))
		{
			header($url);
			header('HTTP/1.1 302 Temporary Redirect');
		}
	}
}
add_action('template_redirect', 'catchkathmandu_rss_redirect');


/**
 * Adds custom classes to the array of body classes.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_body_classes( $classes ) {
	$options  = catchkathmandu_get_options();

	if ( is_page_template( 'page-blog.php') ) {
		$classes[] = 'page-blog';
	}

	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	$layout = catchkathmandu_get_theme_layout();

	$classes[] = $layout;

	if ( $options['disable_responsive'] ) {
		$classes[] = 'responsive-disabled';
	}

	return $classes;
}
add_filter( 'body_class', 'catchkathmandu_body_classes' );


/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'catchkathmandu_enhanced_image_navigation', 10, 2 );


/**
 * Shows Header Right Sidebar
 */
function catchkathmandu_header_right() {

	/* A sidebar in the Header Right
	*/
	get_sidebar( 'header-right' );

}
add_action( 'catchkathmandu_hgroup_wrap', 'catchkathmandu_header_right', 15 );


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'catchkathmandu_page_menu_args' );


/**
 * Removes div from wp_page_menu() and replace with ul.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_wp_page_menu ($page_markup) {
    preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
        $divclass = $matches[1];
        $replace = array('<div class="'.$divclass.'">', '</div>');
        $new_markup = str_replace($replace, '', $page_markup);
        $new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
        return $new_markup; }

add_filter( 'wp_page_menu', 'catchkathmandu_wp_page_menu' );


/**
 * Function to pass the slider effect parameters from php file to js file.
 */
function catchkathmandu_pass_slider_value() {
	$options  = catchkathmandu_get_options();

	$transition_effect = $options['transition_effect'];
	$transition_delay = $options['transition_delay'] * 1000;
	$transition_duration = $options['transition_duration'] * 1000;
	wp_localize_script(
		'catchkathmandu-slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}// catchkathmandu_pass_slider_value


if ( ! function_exists( 'catchkathmandu_post_sliders' ) ) :
/**
 * Template for Featued Post Slider
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_post_sliders(), and that function will be used instead.
 *
 * @uses catchkathmandu_header action to add it in the header
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_post_sliders() {
	//delete_transient( 'catchkathmandu_post_sliders' );

	global $post;
	$options  = catchkathmandu_get_options();


	if ( ( !$catchkathmandu_post_sliders = get_transient( 'catchkathmandu_post_sliders' ) ) && !empty( $options['featured_slider'] ) ) {
		echo '<!-- refreshing cache -->';

		$catchkathmandu_post_sliders = '
		<div id="main-slider" class="container">
        	<section class="featured-slider">';
				$loop = new WP_Query( array(
					'posts_per_page' => $options['slider_qty'],
					'post__in'		 => $options['featured_slider'],
					'orderby' 		 => 'post__in',
					'ignore_sticky_posts' => 1 // ignore sticky posts
				));
				$i=0; while ( $loop->have_posts()) : $loop->the_post(); $i++;
					$title_attribute = the_title_attribute( 'echo=0' );
					$excerpt = get_the_excerpt();
					if ( $i == 1 ) { $classes = 'post postid-'.$post->ID.' hentry slides displayblock'; } else { $classes = 'post postid-'.$post->ID.' hentry slides displaynone'; }
					$catchkathmandu_post_sliders .= '
					<article class="'.$classes.'">
						<figure class="slider-image">
							<a title="' . $title_attribute . '" href="' . esc_url( get_permalink() ) . '">
								'. get_the_post_thumbnail( $post->ID, 'slider', array( 'title' => $title_attribute, 'alt' => $title_attribute, 'class'	=> 'pngfix' ) ).'
							</a>
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="' . $title_attribute . '" href="' . esc_url( get_permalink() ) . '">'.the_title( '<span>','</span>', false ).'</a>
								</h1>
							</header>';
							if ( $excerpt !='') {
								$catchkathmandu_post_sliders .= '<div class="entry-content">'. $excerpt.'</div>';
							}
							$catchkathmandu_post_sliders .= '
						</div>
					</article><!-- .slides -->';
				endwhile; wp_reset_postdata();
				$catchkathmandu_post_sliders .= '
			</section>
        	<div id="slider-nav">
        		<a class="slide-previous">&lt;</a>
        		<a class="slide-next">&gt;</a>
        	</div>
        	<div id="controllers"></div>
  		</div><!-- #main-slider -->';

	set_transient( 'catchkathmandu_post_sliders', $catchkathmandu_post_sliders, 86940 );
	}
	echo $catchkathmandu_post_sliders;
} // catchkathmandu_post_sliders
endif;


if ( ! function_exists( 'catchkathmandu_category_sliders' ) ) :
/**
 * Template for Featured Category Slider
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_category_sliders(), and that function will be used instead.
 *
 * @uses catchkathmandu_header action to add it in the header
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_category_sliders() {
	//delete_transient( 'catchkathmandu_category_sliders' );

	global $post;
	$options  = catchkathmandu_get_options();


	if ( ( !$catchkathmandu_category_sliders = get_transient( 'catchkathmandu_category_sliders' ) ) ) {
		echo '<!-- refreshing cache -->';

		$catchkathmandu_category_sliders = '
		<div id="main-slider" class="container">
        	<section class="featured-slider">';
				$loop = new WP_Query( array(
					'posts_per_page'		=> $options['slider_qty'],
					'category__in'			=> $options['slider_category'],
					'ignore_sticky_posts' 	=> 1 // ignore sticky posts
				));
				$i=0; while ( $loop->have_posts()) : $loop->the_post(); $i++;
					$title_attribute = the_title_attribute( 'echo=0' );
					$excerpt = get_the_excerpt();
					if ( $i == 1 ) { $classes = 'post pageid-'.$post->ID.' hentry slides displayblock'; } else { $classes = 'post pageid-'.$post->ID.' hentry slides displaynone'; }
					$catchkathmandu_category_sliders .= '
					<article class="'.$classes.'">
						<figure class="slider-image">
							<a title="' . $title_attribute . '" href="' . esc_url( get_permalink() ) . '">
								'. get_the_post_thumbnail( $post->ID, 'slider', array( 'title' => $title_attribute, 'alt' => $title_attribute, 'class'	=> 'pngfix' ) ).'
							</a>
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="' . $title_attribute . '" href="' . esc_url( get_permalink() ) . '">'.the_title( '<span>','</span>', false ).'</a>
								</h1>
							</header>';
							if ( $excerpt !='') {
								$catchkathmandu_category_sliders .= '<div class="entry-content">'. $excerpt.'</div>';
							}
							$catchkathmandu_category_sliders .= '
						</div>
					</article><!-- .slides -->';
				endwhile; wp_reset_postdata();
				$catchkathmandu_category_sliders .= '
			</section>
        	<div id="slider-nav">
        		<a class="slide-previous">&lt;</a>
        		<a class="slide-next">&gt;</a>
        	</div>
        	<div id="controllers"></div>
  		</div><!-- #main-slider -->';

	set_transient( 'catchkathmandu_category_sliders', $catchkathmandu_category_sliders, 86940 );
	}
	echo $catchkathmandu_category_sliders;
} // catchkathmandu_category_sliders
endif;


/**
 * Shows Default Slider Demo if there is not iteam in Featured Post Slider
 */
function catchkathmandu_default_sliders() {
	//delete_transient( 'catchkathmandu_default_sliders' );

	if ( !$catchkathmandu_default_sliders = get_transient( 'catchkathmandu_default_sliders' ) ) {
		echo '<!-- refreshing cache -->';
		$catchkathmandu_default_sliders = '
		<div id="main-slider" class="container">
			<section class="featured-slider">

				<article class="post hentry slides demo-image displayblock">
					<figure class="slider-image">
						<a title="Kathmandu Durbar Square" href="#">
							<img src="'. trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/kathmandu-durbar-square-1280x600.jpg" class="wp-post-image" alt="Kathmandu Durbar Square" title="Kathmandu Durbar Square">
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Kathmandu Durbar Square" href="#"><span>Kathmandu Durbar Square</span></a>
							</h1>
						</header>
						<div class="entry-content">
							<p>The Kathmandu Durbar Square holds the palaces of the Malla and Shah kings who ruled over the city. Along with these palaces, the square surrounds quadrangles revealing courtyards and temples.</p>
						</div>
					</div>
				</article><!-- .slides -->

				<article class="post hentry slides demo-image displaynone">
					<figure class="slider-image">
						<a title="Seto Ghumba" href="#">
							<img src="'. trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/seto-ghumba-1280x600.jpg" class="wp-post-image" alt="Seto Ghumba" title="Seto Ghumba">
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Seto Ghumba" href="#"><span>Seto Ghumba</span></a>
							</h1>
						</header>
						<div class="entry-content">
							<p>Situated western part in the outskirts of the Kathmandu valley, Seto Gumba also known as Druk Amitabh Mountain or White Monastery, is one of the most popular Buddhist monasteries of Nepal.</p>
						</div>
					</div>
				</article><!-- .slides -->

				<article class="post hentry slides demo-image displaynone">
					<figure class="slider-image">
						<a title="Nagarkot Himalayan Range" href="#">
							<img src="'. trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/nagarkot-mountain-view1280x600.jpg" class="wp-post-image" alt="Nagarkot Himalayan Range" title="Nagarkot Himalayan Range">
						</a>
					</figure>
					<div class="entry-container">
						<header class="entry-header">
							<h1 class="entry-title">
								<a title="Nagarkot" href="#"><span>Nagarkot</span></a>
							</h1>
						</header>
						<div class="entry-content">
							<p>Nagarkot is renowned for its sunrise view of the Himalaya including Mount Everest as well as other snow-capped peaks of the Himalayan range of eastern Nepal.</p>
						</div>
					</div>
				</article><!-- .slides -->

			</section>
			<div id="slider-nav">
				<a class="slide-previous">&lt;</a>
				<a class="slide-next">&gt;</a>
			</div>
			<div id="controllers"></div>
		</div><!-- #main-slider -->';

	set_transient( 'catchkathmandu_default_sliders', $catchkathmandu_default_sliders, 86940 );
	}
	echo $catchkathmandu_default_sliders;
} // catchkathmandu_default_sliders


/**
 * Shows Slider
 */
function catchkathmandu_slider_display() {
	global $post, $wp_query;
   	$options  = catchkathmandu_get_options();

	// get data value from theme options
	$enableslider = $options['enable_slider'];
	$slidertype = $options['select_slider_type'];

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( ( 'enable-slider-allpage' == $enableslider  ) || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'enable-slider-homepage' == $enableslider  ) ) :
		// This function passes the value of slider effect to js file
		if ( function_exists( 'catchkathmandu_pass_slider_value' ) ) : catchkathmandu_pass_slider_value(); endif;
		// Select Slider
		if (  'post-slider' == $slidertype  && !empty( $options['featured_slider'] ) && function_exists( 'catchkathmandu_post_sliders' ) ) {
			catchkathmandu_post_sliders();
		}
		elseif (  'category-slider' == $slidertype && function_exists( 'catchkathmandu_category_sliders' ) ) {
			catchkathmandu_category_sliders();
		}
		else {
			catchkathmandu_default_sliders();
		}
	endif;
}
add_action( 'catchkathmandu_before_main', 'catchkathmandu_slider_display', 10 );


if ( ! function_exists( 'catchkathmandu_homepage_headline' ) ) :
/**
 * Template for Homepage Headline
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_homepage_headline(), and that function will be used instead.
 *
 * @uses catchkathmandu_before_main action to add it in the header
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_homepage_headline() {
	//delete_transient( 'catchkathmandu_homepage_headline' );

	global $post, $wp_query;
   	$options  = catchkathmandu_get_options();

	// Getting data from Theme Options
	$disable_headline = $options['disable_homepage_headline'];
	$disable_subheadline = $options['disable_homepage_subheadline'];
	$disable_button = $options['disable_homepage_button'];
	$homepage_headline = $options['homepage_headline'];
	$homepage_subheadline = $options['homepage_subheadline'];
	$homepage_headline_button = $options['homepage_headline_button'];
	$homepage_headline_url = $options['homepage_headline_url'];

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	 if ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && ( empty( $disable_headline ) || empty( $disable_subheadline ) || empty( $disable_button ) ) ) {

		if ( !$catchkathmandu_homepage_headline = get_transient( 'catchkathmandu_homepage_headline' ) ) {

			echo '<!-- refreshing cache -->';

			$catchkathmandu_homepage_headline = '<div id="homepage-message" class="container"><div class="left-section">';

			if ( $disable_headline == "0" ) {
				$catchkathmandu_homepage_headline .= '<h2>' . $homepage_headline . '</h2>';
			}
			if ( $disable_subheadline == "0" ) {
				$catchkathmandu_homepage_headline .= '<p>' . $homepage_subheadline . '</p>';
			}

			$catchkathmandu_homepage_headline .= '</div><!-- .left-section -->';

			if ( !empty ( $homepage_headline_url ) && $disable_button == "0" ) {
				$catchkathmandu_homepage_headline .= '<div class="right-section"><a href="' . $homepage_headline_url . '" target="_blank">' . $homepage_headline_button . '</a></div><!-- .right-section -->';
			}

			$catchkathmandu_homepage_headline .= '</div><!-- #homepage-message -->';

			set_transient( 'catchkathmandu_homepage_headline', $catchkathmandu_homepage_headline, 86940 );
		}
		echo $catchkathmandu_homepage_headline;
	 }
}
endif; // catchkathmandu_homepage_featured_content

add_action( 'catchkathmandu_before_main', 'catchkathmandu_homepage_headline', 10 );


/**
 * Shows Default Featued Content
 *
 * @uses catchkathmandu_before_main action to add it in the header
 */
function catchkathmandu_default_featured_content() {
	//delete_transient( 'catchkathmandu_default_featured_content' );

	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();
	$disable_homepage_featured = $options['disable_homepage_featured'];
	$headline = $options['homepage_featured_headline'];
	$layouts = $options['homepage_featured_layout'];

	if ( $disable_homepage_featured == "0" ) {
		if ( !$output = get_transient( 'catchkathmandu_default_featured_content' ) ) {
			//Checking Layout
			if ( 'four-columns' == $layouts  ) {
				$classes = "layout-four";
			}
			else {
				$classes = "layout-three";
			}

			$output = '
			<section id="featured-post" class="' . $classes . '">
				<h1 id="feature-heading" class="entry-title">Popular Places</h1>
				<div class="featued-content-wrap">
					<article id="featured-post-1" class="post hentry post-demo">
						<figure class="featured-homepage-image">
							<a href="#" title="Spectacular Dhulikhel">
								<img title="Spectacular Dhulikhel" alt="Spectacular Dhulikhel" class="wp-post-image" src="'.trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/spectacular-dhulikhel-360x240.jpg" />
							</a>
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="Spectacular Dhulikhel" href="#">Spectacular Dhulikhel</a>
								</h1>
							</header>
							<div class="entry-content">
								The Mountains - A Tourist Paradise: The spectacular snowfed mountains seen from Dhuklikhel must be one of the finest panoramic views in the world.
							</div>
						</div><!-- .entry-container -->
					</article>

					<article id="featured-post-2" class="post hentry post-demo">
						<figure class="featured-homepage-image">
							<a href="#" title="Swayambhunath">
								<img title="Swayambhunath" alt="Swayambhunath" class="wp-post-image" src="'.trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/swayambhunath-360x240.jpg" />
							</a>
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="Swayambhunath" href="#">Swayambhunath</a>
								</h1>
							</header>
							<div class="entry-content">
								Swayambhunath is an ancient religious site up in the hill around Kathmandu Valley. It is also known as the Monkey Temple as there are holy monkeys living in the temple.
							</div>
						</div><!-- .entry-container -->
					</article>

					<article id="featured-post-3" class="post hentry post-demo">
						<figure class="featured-homepage-image">
							<a href="#" title="Wood Art">
								<img title="Wood Art" alt="Wood Art" class="wp-post-image" src="'.trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/wood-art-360x240.jpg" />
							</a>
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="Wood Art" href="#">Wood Art</a>
								</h1>
							</header>
							<div class="entry-content">
								It is the traditional architecture in the Kathmandu valley in temples, palaces, monasteries and houses a perfected Neawri art form generally carved very artistically out of  Wood.

							</div>
						</div><!-- .entry-container -->
					</article>

					<article id="featured-post-4" class="post hentry post-demo">
						<figure class="featured-homepage-image">
							<a href="#" title="Nepal Prayer Wheels">
								<img title="Nepal Prayer Wheels" alt="Nepal Prayer Wheels" class="wp-post-image" src="'.trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'images/demo/nepal-prayer-wheels-360x240.jpg" />
							</a>
						</figure>
						<div class="entry-container">
							<header class="entry-header">
								<h1 class="entry-title">
									<a title="Nepal Prayer Wheels" href="#">Nepal Prayer Wheels</a>
								</h1>
							</header>
							<div class="entry-content">
								A Prayer wheel is a cylindrical wheel on a spindle made from metal, wood, stone, leather or coarse cotton. The practitioner most often spins the wheel clockwise.
							</div>
						</div><!-- .entry-container -->
					</article>
				</div><!-- .featued-content-wrap -->
			</section><!-- #featured-post -->';
		}
		echo $output;
	}
}


if ( ! function_exists( 'catchkathmandu_homepage_featured_content' ) ) :
/**
 * Template for Homepage Featured Content
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_homepage_featured_content(), and that function will be used instead.
 *
 * @uses catchkathmandu_before_main action to add it in the header
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_homepage_featured_content() {
	//delete_transient( 'catchkathmandu_homepage_featured_content' );

	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();
	$disable_homepage_featured = $options['disable_homepage_featured'];
	$quantity = $options['homepage_featured_qty'];
	$headline = $options['homepage_featured_headline'];
	$layouts = $options['homepage_featured_layout'];

	if ( $disable_homepage_featured == "0" ) {

		if ( !$output = get_transient( 'catchkathmandu_homepage_featured_content' )  && ( !empty( $options['homepage_featured_image'] ) || !empty( $options['homepage_featured_title'] ) || !empty( $options['homepage_featured_content'] ) ) ) {

			echo '<!-- refreshing cache -->';

			//Checking Layout
			if ( 'four-columns' == $layouts  ) {
				$classes = "layout-four";
			}
			else {
				$classes = "layout-three";
			}

			$output = '<section id="featured-post" class="' . $classes . '">';

			if ( !empty( $headline ) ) {
				$output .= '<h1 id="feature-heading" class="entry-title">' . wp_kses_post( $headline ) . '</h1>';
			}

			$output .= '<div class="featued-content-wrap">';

				for ( $i = 1; $i <= $quantity; $i++ ) {
					//Checking Link
					if ( !empty ( $options['homepage_featured_url'][ $i ] ) ) {
						//support qTranslate plugin
						if ( function_exists( 'qtrans_convertURL' ) ) {
							$link = qtrans_convertURL($options['homepage_featured_url'][ $i ]);
						}
						else {
							$link = $options['homepage_featured_url'][ $i ];
						}
						if ( !empty ( $options['homepage_featured_base'][ $i ] ) ) {
							$target = '_blank';
						}
						else {
							$target = '_self';
						}
					} else {
						$link = '';
						$target = '';
					}

					//Checking Title
					if ( !empty ( $options['homepage_featured_title'][ $i ] ) ) {
						$title = $options['homepage_featured_title'][ $i ];
					} else {
						$title = '';
					}

					if ( !empty ( $options['homepage_featured_title'][ $i ] ) || !empty ( $options['homepage_featured_content'][ $i ] ) || !empty ( $options['homepage_featured_image'][ $i ] ) ) {
						$output .= '
						<article id="featured-post-'.$i.'" class="post hentry">';
							if ( !empty ( $options['homepage_featured_image'][ $i ] ) ) {
								$output .= '<figure class="featured-homepage-image">';

									if ( !empty ( $link ) ) {
										$output .= '
										<a title="' . esc_attr( $title ) . '" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">
											<img src="' . esc_url( $options['homepage_featured_image'][ $i ] ) . '" class="wp-post-image" alt="' . esc_attr( $title ) . '" title="' . esc_attr( $title ) . '">
										</a>';
									}
									else {
										$output .= '
										<img src="' . esc_url( $options['homepage_featured_image'][ $i ] ) . '" class="wp-post-image" alt="' . esc_attr( $title ) . '" title="' . esc_attr( $title ) . '">';
									}

								$output .= '</figure>';
							}
							if ( !empty ( $options['homepage_featured_title'][ $i ] ) || !empty ( $options['homepage_featured_content'][ $i ] ) ) {
								$output .= '<div class="entry-container">';

									if ( !empty ( $title ) ) {

										$output .= '
										<header class="entry-header">
											<h1 class="entry-title">';
												if ( !empty ( $link ) ) {
													$output .= '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '" target="' . esc_attr( $target ) . '">' . $title . '</a>';
												}
												else {
													$output .= $title;
												}
											$output .= '
											</h1>
										</header>';

									}
									if ( !empty ( $options['homepage_featured_content'][ $i ] ) ) {

										$output .= '
										<div class="entry-content">
											' . $options['homepage_featured_content'][ $i ] . '
										</div>';

									}
								$output .= '
								</div><!-- .entry-container -->';
							}
						$output .= '
						</article><!-- .post -->';
					}

				}

			$output .= '</div><!-- .featued-content-wrap -->';

			$output .= '</section><!-- #featured-post -->';

		}

		echo $output;

	}

}
endif; // catchkathmandu_homepage_featured_content


/**
 * Homepage Featured Content
 *
 */
function catchkathmandu_homepage_featured_display() {
	global $wp_query;

	// Getting data from Theme Options
   	$options  = catchkathmandu_get_options();
	$disable_homepage_featured = $options['disable_homepage_featured'];

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( is_customize_preview() ) {
		// Adding block in customizer preview as globals add empty arrays.
		$options['homepage_featured_image']   = array_filter( $options['homepage_featured_image'] );
		$options['homepage_featured_title']   = array_filter( $options['homepage_featured_title'] );
		$options['homepage_featured_content'] = array_filter( $options['homepage_featured_content'] );
	}

	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
		if  ( !empty( $options['homepage_featured_image'] ) || !empty( $options['homepage_featured_title'] ) || !empty( $options['homepage_featured_content'] ) ) {
			catchkathmandu_homepage_featured_content();
		} else {
			catchkathmandu_default_featured_content();
		}
	}

} // catchkathmandu_homepage_featured_content


if ( ! function_exists( 'catchkathmandu_homepage_featured_position' ) ) :
/**
 * Homepage Featured Content Position
 *
 */
function catchkathmandu_homepage_featured_position() {
	// Getting data from Theme Options
	$options  = catchkathmandu_get_options();
	$moveposition = $options['move_posts_home'];

	if ( empty( $moveposition ) ) {
		add_action( 'catchkathmandu_main', 'catchkathmandu_homepage_featured_display', 10 );
	} else {
		add_action( 'catchkathmandu_after_secondary', 'catchkathmandu_homepage_featured_display', 10 );
	}

}
endif; // catchkathmandu_homepage_featured_position
add_action( 'catchkathmandu_before_main', 'catchkathmandu_homepage_featured_position', 10 );


if ( ! function_exists( 'catchkathmandu_content_sidebar_wrap_start' ) ) :
/**
 * Div ID content-sidebar-wrap start
 *
 */
function catchkathmandu_content_sidebar_wrap_start() {
	echo '<div id="content-sidebar-wrap">';
}
endif; // catchkathmandu_content_sidebar_wrap_start

add_action( 'catchkathmandu_content_sidebar_start', 'catchkathmandu_content_sidebar_wrap_start', 10 );


if ( ! function_exists( 'catchkathmandu_content_sidebar_wrap_end' ) ) :
/**
 * Div ID content-sidebar-wrap end
 *
 */
function catchkathmandu_content_sidebar_wrap_end() {
	echo '</div><!-- #content-sidebar-wrap -->';
}
endif; // catchkathmandu_content_sidebar_wrap_end

add_action( 'catchkathmandu_content_sidebar_end', 'catchkathmandu_content_sidebar_wrap_end', 10 );


/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function catchkathmandu_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}


if ( ! function_exists( 'catchkathmandu_footer_content' ) ) :
/**
 * Template for Footer Content
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_footer_content(), and that function will be used instead.
 *
 * @uses catchkathmandu_site_generator action to add it in the footer
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_footer_content() {
	//delete_transient( 'catchkathmandu_footer_content_new' );

	if ( ! $catchkathmandu_footer_content = get_transient( 'catchkathmandu_footer_content_new' ) ) {
		echo '<!-- refreshing cache -->';

		// get value from default value.
		$options = catchkathmandu_get_defaults();

      	$catchkathmandu_footer_content = $options['footer_code'];

    	set_transient( 'catchkathmandu_footer_content_new', $catchkathmandu_footer_content, 86940 );
    }

	echo $catchkathmandu_footer_content;
}
endif;
add_action( 'catchkathmandu_site_generator', 'catchkathmandu_footer_content', 10 );


/**
 * Alter the query for the main loop in homepage
 * @uses pre_get_posts hook
 */
function catchkathmandu_alter_home( $query ){
	if ( $query->is_main_query() && $query->is_home() ) {
		$options  = catchkathmandu_get_options();

	    $cats = $options['front_page_category'];

	    if ( $options['exclude_slider_post'] != "0" && !empty( $options['featured_slider'] ) ) {
			$query->query_vars['post__not_in'] = $options['featured_slider'];
			$query->query_vars['category__not_in'] = $options['slider_category'];
		}

		if ( is_array( $cats ) && !in_array( '0', $cats ) ) {
			$query->query_vars['category__in'] = $cats;
		}
	}
}
add_action( 'parse_query','catchkathmandu_alter_home', 5 );


if ( ! function_exists( 'catchkathmandu_social_networks' ) ) :
/**
 * Template for Social Icons
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_social_networks(), and that function will be used instead.
 *
 * @since Catch Kathmandu Pro 1.0
 */
function catchkathmandu_social_networks() {
	//delete_transient( 'catchkathmandu_social_networks' );

	// get the data value from theme options
	$options  = catchkathmandu_get_options();

    $elements = array();

	$elements = array( 	$options['social_facebook'],
						$options['social_twitter'],
						$options['social_googleplus'],
						$options['social_linkedin'],
						$options['social_pinterest'],
						$options['social_youtube'],
						$options['social_vimeo'],
						$options['social_slideshare'],
						$options['social_foursquare'],
						$options['social_flickr'],
						$options['social_tumblr'],
						$options['social_deviantart'],
						$options['social_dribbble'],
						$options['social_myspace'],
						$options['social_wordpress'],
						$options['social_rss'],
						$options['social_delicious'],
						$options['social_lastfm'],
						$options['social_instagram'],
						$options['social_github'],
						$options['social_vkontakte'],
						$options['social_myworld'],
						$options['social_odnoklassniki'],
						$options['social_goodreads'],
						$options['social_skype'],
						$options['social_soundcloud'],
						$options[ 'social_email'],
						$options['social_xing'],
						$options['social_meetup'],
						$options['social_x'],
						$options['social_bluesky'],
						$options['social_tiktok'],
						$options['social_threads']
					);
	$flag = 0;
	if ( !empty( $elements ) ) {
		foreach( $elements as $option) {
			if ( !empty( $option ) ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if ( $flag == 1 ) {
				break;
			}
		}
	}

	if ( ( !$catchkathmandu_social_networks = get_transient( 'catchkathmandu_social_networks' ) ) && ( $flag == 1 ) )  {
		echo '<!-- refreshing cache -->';

		$catchkathmandu_social_networks .='
		<ul class="social-profile">';

			//facebook
			if ( !empty( $options['social_facebook'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="facebook"><a href="'.esc_url( $options['social_facebook'] ).'" title="'. esc_attr__( 'Facebook', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Facebook', 'catch-kathmandu' ) .'</a></li>';
			}
			//Twitter
			if ( !empty( $options['social_twitter'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="twitter"><a href="'.esc_url( $options['social_twitter'] ).'" title="'. esc_attr__( 'Twitter', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Twitter', 'catch-kathmandu' ) .'</a></li>';
			}
			//Google+
			if ( !empty( $options['social_googleplus'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="google-plus"><a href="'.esc_url( $options['social_googleplus'] ).'" title="'. esc_attr__( 'Google+', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Google+', 'catch-kathmandu' ) .'</a></li>';
			}
			//Linkedin
			if ( !empty( $options['social_linkedin'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="linkedin"><a href="'.esc_url( $options['social_linkedin'] ).'" title="'. esc_attr__( 'LinkedIn', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'LinkedIn', 'catch-kathmandu' ) .'</a></li>';
			}
			//Pinterest
			if ( !empty( $options['social_pinterest'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="pinterest"><a href="'.esc_url( $options['social_pinterest'] ).'" title="'. esc_attr__( 'Pinterest', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Pinterest', 'catch-kathmandu' ) .'</a></li>';
			}
			//YouTube
			if ( !empty( $options['social_youtube'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="you-tube"><a href="'.esc_url( $options['social_youtube'] ).'" title="'. esc_attr__( 'YouTube', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'YouTube', 'catch-kathmandu' ) .'</a></li>';
			}
			//Vimeo
			if ( !empty( $options['social_vimeo'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="viemo"><a href="'.esc_url( $options['social_vimeo'] ).'" title="'. esc_attr__( 'Vimeo', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Vimeo', 'catch-kathmandu' ) .'</a></li>';
			}
			//Slideshare
			if ( !empty( $options['social_slideshare'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="slideshare"><a href="'.esc_url( $options['social_slideshare'] ).'" title="'. esc_attr__( 'SlideShare', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'SlideShare', 'catch-kathmandu' ) .'</a></li>';
			}
			//FourSquare
			if ( !empty( $options['social_foursquare'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="foursquare"><a href="'.esc_url( $options['social_foursquare'] ).'" title="'. esc_attr__( 'FourSquare', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'FourSquare', 'catch-kathmandu' ) .'</a></li>';
			}
			//Flickr
			if ( !empty( $options['social_flickr'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="flickr"><a href="'.esc_url( $options['social_flickr'] ).'" title="'. esc_attr__( 'Flickr', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Flickr', 'catch-kathmandu' ) .'</a></li>';
			}
			//Tumblr
			if ( !empty( $options['social_tumblr'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="tumblr"><a href="'.esc_url( $options['social_tumblr'] ).'" title="'. esc_attr__( 'Tumblr', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Tumblr', 'catch-kathmandu' ) .'</a></li>';
			}
			//deviantART
			if ( !empty( $options['social_deviantart'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="deviantart"><a href="'.esc_url( $options['social_deviantart'] ).'" title="'. esc_attr__( 'deviantART', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'deviantART', 'catch-kathmandu' ) .'</a></li>';
			}
			//Dribbble
			if ( !empty( $options['social_dribbble'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="dribbble"><a href="'.esc_url( $options['social_dribbble'] ).'" title="'. esc_attr__( 'Dribbble', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Dribbble', 'catch-kathmandu' ) .'</a></li>';
			}
			//MySpace
			if ( !empty( $options['social_myspace'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="myspace"><a href="'.esc_url( $options['social_myspace'] ).'" title="'. esc_attr__( 'MySpace', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'MySpace', 'catch-kathmandu' ) .'</a></li>';
			}
			//WordPress
			if ( !empty( $options['social_wordpress'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="wordpress"><a href="'.esc_url( $options['social_wordpress'] ).'" title="'. esc_attr__( 'WordPress', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'WordPress', 'catch-kathmandu' ) .'</a></li>';
			}
			//RSS
			if ( !empty( $options['social_rss'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="rss"><a href="'.esc_url( $options['social_rss'] ).'" title="'. esc_attr__( 'RSS', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'RSS', 'catch-kathmandu' ) .'</a></li>';
			}
			//Delicious
			if ( !empty( $options['social_delicious'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="delicious"><a href="'.esc_url( $options['social_delicious'] ).'" title="'. esc_attr__( 'Delicious', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Delicious', 'catch-kathmandu' ) .'</a></li>';
			}
			//Last.fm
			if ( !empty( $options['social_lastfm'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="lastfm"><a href="'.esc_url( $options['social_lastfm'] ).'" title="'. esc_attr__( 'Last.fm', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Last.fm', 'catch-kathmandu' ) .'</a></li>';
			}
			//Instagram
			if ( !empty( $options['social_instagram'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="instagram"><a href="'.esc_url( $options['social_instagram'] ).'" title="'. esc_attr__( 'Instagram', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Instagram', 'catch-kathmandu' ) .'</a></li>';
			}
			//GitHub
			if ( !empty( $options['social_github'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="github"><a href="'.esc_url( $options['social_github'] ).'" title="'. esc_attr__( 'GitHub', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'GitHub', 'catch-kathmandu' ) .'</a></li>';
			}
			//Vkontakte
			if ( !empty( $options['social_vkontakte'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="vkontakte"><a href="'.esc_url( $options['social_vkontakte'] ).'" title="'. esc_attr__( 'Vkontakte', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Vkontakte', 'catch-kathmandu' ) .'</a></li>';
			}
			//My World
			if ( !empty( $options['social_myworld'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="myworld"><a href="'.esc_url( $options['social_myworld'] ).'" title="'. esc_attr__( 'My World', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'My World', 'catch-kathmandu' ) .'</a></li>';
			}
			//Odnoklassniki
			if ( !empty( $options['social_odnoklassniki'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="odnoklassniki"><a href="'.esc_url( $options['social_odnoklassniki'] ).'" title="'. esc_attr__( 'Odnoklassniki', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Odnoklassniki', 'catch-kathmandu' ) .'</a></li>';
			}
			//Goodreads
			if ( !empty( $options['social_goodreads'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="goodreads"><a href="'.esc_url( $options['social_goodreads'] ).'" title="'. esc_attr__( 'GoodReads', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'GoodReads', 'catch-kathmandu' ) .'</a></li>';
			}
			//Skype
			if ( !empty( $options['social_skype'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="skype"><a href="'.esc_attr( $options['social_skype'] ).'" title="'. esc_attr__( 'Skype', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Skype', 'catch-kathmandu' ) .'</a></li>';
			}
			//Soundcloud
			if ( !empty( $options['social_soundcloud'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="soundcloud"><a href="'.esc_url( $options['social_soundcloud'] ).'" title="'. esc_attr__( 'SoundCloud', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'SoundCloud', 'catch-kathmandu' ) .'</a></li>';
			}
			//Email
			if ( !empty( $options['social_email'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="email"><a href="mailto:'.sanitize_email( $options['social_email'] ).'" title="'. esc_attr__( 'Email', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Email', 'catch-kathmandu' ) .'</a></li>';
			}
			//Contact
			if ( !empty( $options['social_contact'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="contactus"><a href="'.esc_url( $options['social_contact'] ).'" title="'. esc_attr__( 'Contact', 'catch-kathmandu' ) .'">'. esc_attr__( 'Contact', 'catch-kathmandu' ) .'</a></li>';
			}
			//Xing
			if ( !empty( $options['social_xing'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="xing"><a href="'.esc_url( $options['social_xing'] ).'" title="'. esc_attr__( 'Xing', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Xing', 'catch-kathmandu' ) .'</a></li>';
			}
			//Meetup
			if ( !empty( $options['social_meetup'] ) ) {
				$catchkathmandu_social_networks .=
					'<li class="meetup"><a href="'.esc_url( $options['social_meetup'] ).'" title="'. esc_attr__( 'Meetup', 'catch-kathmandu' ) .'" target="_blank">'. esc_attr__( 'Meetup', 'catch-kathmandu' ) .'</a></li>';
			}
			//X Twitter
			if (!empty($options['social_x'])) {
				$catchkathmandu_social_networks .=
					'<li class="x"><a href="' . esc_url($options['social_x']) . '" title="' . esc_attr__('X Twitter', 'catch-kathmandu') . '" target="_blank">' . esc_attr__('X Twitter', 'catch-kathmandu') . '</a></li>';
			}
			//Bluesky
			if (!empty($options['social_bluesky'])) {
				$catchkathmandu_social_networks .=
					'<li class="bluesky"><a href="' . esc_url($options['social_bluesky']) . '" title="' . esc_attr__('Bluesky', 'catch-kathmandu') . '" target="_blank">' . esc_attr__('Bluesky', 'catch-kathmandu') . '</a></li>';
			}
			//Tiktok
			if (!empty($options['social_tiktok'])) {
				$catchkathmandu_social_networks .=
					'<li class="tiktok"><a href="' . esc_url($options['social_tiktok']) . '" title="' . esc_attr__('Tiktok', 'catch-kathmandu') . '" target="_blank">' . esc_attr__('Tiktok', 'catch-kathmandu') . '</a></li>';
			}
			//Threads
			if (!empty($options['social_threads'])) {
				$catchkathmandu_social_networks .=
					'<li class="threads"><a href="' . esc_url($options['social_threads']) . '" title="' . esc_attr__('Threads', 'catch-kathmandu') . '" target="_blank">' . esc_attr__('Threads', 'catch-kathmandu') . '</a></li>';
			}

			$catchkathmandu_social_networks .='
		</ul>';

		set_transient( 'catchkathmandu_social_networks', $catchkathmandu_social_networks, 86940 );
	}
	echo $catchkathmandu_social_networks;
}
endif; // catchkathmandu_social_networks


/**
 * Site Verification and Header Code from the Theme Option
 *
 * If user sets the code we're going to display meta verification
 * @get the data value from theme options
 * @uses wp_head action to add the code in the header
 * @uses set_transient and delete_transient API for cache
 */
function catchkathmandu_webmaster() {
	//delete_transient( 'catchkathmandu_webmaster' );

	if ( ( !$catchkathmandu_webmaster = get_transient( 'catchkathmandu_webmaster' ) ) ) {

		// get the data value from theme options
		$options  = catchkathmandu_get_options();
		echo '<!-- refreshing cache -->';

		$catchkathmandu_webmaster = '';
		//google
		if ( !empty( $options['google_verification'] ) ) {
			$catchkathmandu_webmaster .= '<meta name="google-site-verification" content="' .  $options['google_verification'] . '" />' . "\n";
		}
		//bing
		if ( !empty( $options['bing_verification'] ) ) {
			$catchkathmandu_webmaster .= '<meta name="msvalidate.01" content="' .  $options['bing_verification']  . '" />' . "\n";
		}
		//yahoo
		 if ( !empty( $options['yahoo_verification'] ) ) {
			$catchkathmandu_webmaster .= '<meta name="y_key" content="' .  $options['yahoo_verification']  . '" />' . "\n";
		}
		//site stats, analytics header code
		if ( !empty( $options['analytic_header'] ) ) {
			$catchkathmandu_webmaster =  $options['analytic_header'] ;
		}

		set_transient( 'catchkathmandu_webmaster', $catchkathmandu_webmaster, 86940 );
	}
	echo $catchkathmandu_webmaster;
}
add_action('wp_head', 'catchkathmandu_webmaster');


/**
 * This function loads the Footer Code such as Add this code from the Theme Option
 *
 * @get the data value from theme options
 * @load on the footer ONLY
 * @uses wp_footer action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function catchkathmandu_footercode() {
	//delete_transient( 'catchkathmandu_footercode' );

	if ( ( !$catchkathmandu_footercode = get_transient( 'catchkathmandu_footercode' ) ) ) {

		// get the data value from theme options
		$options  = catchkathmandu_get_options();
		echo '<!-- refreshing cache -->';

		//site stats, analytics header code
		if ( !empty( $options['analytic_footer'] ) ) {
			$catchkathmandu_footercode =  $options['analytic_footer'] ;
		}

		set_transient( 'catchkathmandu_footercode', $catchkathmandu_footercode, 86940 );
	}
	echo $catchkathmandu_footercode;
}
add_action('wp_footer', 'catchkathmandu_footercode');


/**
 * Adds in post and Page ID when viewing lists of posts and pages
 * This will help the admin to add the post ID in featured slider
 *
 * @param mixed $post_columns
 * @return post columns
 */
function catchkathmandu_post_id_column( $post_columns ) {
	$beginning = array_slice( $post_columns, 0 ,1 );
	$beginning[ 'postid' ] = __( 'ID', 'catch-kathmandu'  );
	$ending = array_slice( $post_columns, 1 );
	$post_columns = array_merge( $beginning, $ending );
	return $post_columns;
}
add_filter( 'manage_posts_columns', 'catchkathmandu_post_id_column' );

function catchkathmandu_posts_id_column( $col, $val ) {
	if ( 'postid' == $col  ) echo $val;
}
add_action( 'manage_posts_custom_column', 'catchkathmandu_posts_id_column', 10, 2 );

function catchkathmandu_posts_id_column_css() {
	echo '
	<style type="text/css">
	    #postid { width: 80px; }
	    @media screen and (max-width: 782px) {
	        .wp-list-table #postid, .wp-list-table #the-list .postid { display: none; }
	        .wp-list-table #the-list .is-expanded .postid {
	            padding-left: 30px;
	        }
	    }
    </style>';
}
add_action( 'admin_head-edit.php', 'catchkathmandu_posts_id_column_css' );

if ( ! function_exists( 'catchkathmandu_pagemenu_filter' ) ) :
/**
 * @uses wp_page_menu filter hook
 */
function catchkathmandu_pagemenu_filter( $text ) {
	$replace = array(
		'current_page_item'     => 'current-menu-item'
	);

	$text = str_replace( array_keys( $replace ), $replace, $text );
  	return $text;

}
endif; // catchkathmandu_pagemenu_filter
add_filter('wp_page_menu', 'catchkathmandu_pagemenu_filter');


/**
 * Shows Header Top Sidebar
 */
function catchkathmandu_header_top() {

	/* A sidebar in the Header Top
	*/
	get_sidebar( 'header-top' );

}
add_action( 'catchkathmandu_before_hgroup_wrap', 'catchkathmandu_header_top', 10 );


if ( ! function_exists( 'catchkathmandu_breadcrumb_display' ) ) :
/**
 * Display breadcrumb on header
 */
function catchkathmandu_breadcrumb_display() {
	global $post, $wp_query;

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) {
		return false;
	}
	else {
		if ( function_exists( 'bcn_display_list' ) ) {
			echo
			'<div class="breadcrumb container">
				<ul>';
					bcn_display_list();
					echo '
				</ul>
				<div class="row-end"></div>
			</div> <!-- .breadcrumb -->';
		}
	}

} // catchkathmandu_breadcrumb_display
endif;

// Load  breadcrumb in catchkathmandu_after_hgroup_wrap hook
add_action( 'catchkathmandu_after_hgroup_wrap', 'catchkathmandu_breadcrumb_display', 30 );


/**
 * This function loads Scroll Up Navigation
 *
 * @get the data value from theme options for disable
 * @uses catchkathmandu_after_footer action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function catchkathmandu_scrollup() {
	//delete_transient( 'catchkathmandu_scrollup' );

	if ( !$catchkathmandu_scrollup = get_transient( 'catchkathmandu_scrollup' ) ) {

		// get the data value from theme options
		$options  = catchkathmandu_get_options();
		echo '<!-- refreshing cache -->';

		//site stats, analytics header code
		if ( empty( $options['disable_scrollup'] ) ) {
			$catchkathmandu_scrollup =  '<a href="#masthead" id="scrollup"><span class="screen-reader-text">' . esc_html__( 'Scroll Up', 'catch-kathmandu' ) . '</span></a>' ;
		}

		set_transient( 'catchkathmandu_scrollup', $catchkathmandu_scrollup, 86940 );
	}
	echo $catchkathmandu_scrollup;
}
add_action( 'catchkathmandu_after_footer', 'catchkathmandu_scrollup', 10 );
