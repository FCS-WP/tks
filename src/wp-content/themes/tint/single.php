<?php
/**
 * The template to display single post
 *
 * @package TINT
 * @since TINT 1.0
 */

// Full post loading
$full_post_loading          = tint_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = tint_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = tint_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$tint_related_position   = tint_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$tint_posts_navigation   = tint_get_theme_option( 'posts_navigation' );
$tint_prev_post          = false;
$tint_prev_post_same_cat = (int)tint_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( tint_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	tint_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'tint_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $tint_posts_navigation ) {
		$tint_prev_post = get_previous_post( $tint_prev_post_same_cat );  // Get post from same category
		if ( ! $tint_prev_post && $tint_prev_post_same_cat ) {
			$tint_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $tint_prev_post ) {
			$tint_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $tint_prev_post ) ) {
		tint_sc_layouts_showed( 'featured', false );
		tint_sc_layouts_showed( 'title', false );
		tint_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $tint_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/content', 'single-' . tint_get_theme_option( 'single_style' ) ), 'single-' . tint_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $tint_related_position, 'inside' ) === 0 ) {
		$tint_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'tint_action_related_posts' );
		$tint_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $tint_related_content ) ) {
			$tint_related_position_inside = max( 0, min( 9, tint_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $tint_related_position_inside ) {
				$tint_related_position_inside = mt_rand( 1, 9 );
			}

			$tint_p_number         = 0;
			$tint_related_inserted = false;
			$tint_in_block         = false;
			$tint_content_start    = strpos( $tint_content, '<div class="post_content' );
			$tint_content_end      = strrpos( $tint_content, '</div>' );

			for ( $i = max( 0, $tint_content_start ); $i < min( strlen( $tint_content ) - 3, $tint_content_end ); $i++ ) {
				if ( $tint_content[ $i ] != '<' ) {
					continue;
				}
				if ( $tint_in_block ) {
					if ( strtolower( substr( $tint_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$tint_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $tint_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $tint_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$tint_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $tint_content[ $i + 1 ] && in_array( $tint_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$tint_p_number++;
					if ( $tint_related_position_inside == $tint_p_number ) {
						$tint_related_inserted = true;
						$tint_content = ( $i > 0 ? substr( $tint_content, 0, $i ) : '' )
											. $tint_related_content
											. substr( $tint_content, $i );
					}
				}
			}
			if ( ! $tint_related_inserted ) {
				if ( $tint_content_end > 0 ) {
					$tint_content = substr( $tint_content, 0, $tint_content_end ) . $tint_related_content . substr( $tint_content, $tint_content_end );
				} else {
					$tint_content .= $tint_related_content;
				}
			}
		}

		tint_show_layout( $tint_content );
	}

	// Comments
	do_action( 'tint_action_before_comments' );
	comments_template();
	do_action( 'tint_action_after_comments' );

	// Related posts
	if ( 'below_content' == $tint_related_position
		&& ( 'scroll' != $tint_posts_navigation || (int)tint_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)tint_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'tint_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $tint_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $tint_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $tint_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $tint_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'tint_action_nav_links_single_scroll_data', $tint_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
