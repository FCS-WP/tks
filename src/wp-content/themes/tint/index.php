<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package TINT
 * @since TINT 1.0
 */

$tint_template = apply_filters( 'tint_filter_get_template_part', tint_blog_archive_get_template() );

if ( ! empty( $tint_template ) && 'index' != $tint_template ) {

	get_template_part( $tint_template );

} else {

	tint_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$tint_stickies   = is_home()
								|| ( in_array( tint_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) tint_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$tint_post_type  = tint_get_theme_option( 'post_type' );
		$tint_args       = array(
								'blog_style'     => tint_get_theme_option( 'blog_style' ),
								'post_type'      => $tint_post_type,
								'taxonomy'       => tint_get_post_type_taxonomy( $tint_post_type ),
								'parent_cat'     => tint_get_theme_option( 'parent_cat' ),
								'posts_per_page' => tint_get_theme_option( 'posts_per_page' ),
								'sticky'         => tint_get_theme_option( 'sticky_style', 'inherit' ) == 'columns'
															&& is_array( $tint_stickies )
															&& count( $tint_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		tint_blog_archive_start();

		do_action( 'tint_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'tint_action_before_page_author' );
			get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'tint_action_after_page_author' );
		}

		if ( tint_get_theme_option( 'show_filters', 0 ) ) {
			do_action( 'tint_action_before_page_filters' );
			tint_show_filters( $tint_args );
			do_action( 'tint_action_after_page_filters' );
		} else {
			do_action( 'tint_action_before_page_posts' );
			tint_show_posts( array_merge( $tint_args, array( 'cat' => $tint_args['parent_cat'] ) ) );
			do_action( 'tint_action_after_page_posts' );
		}

		do_action( 'tint_action_blog_archive_end' );

		tint_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'tint_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
