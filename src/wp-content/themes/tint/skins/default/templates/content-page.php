<?php
/**
 * The default template to display the content of the single page
 *
 * @package TINT
 * @since TINT 1.0
 */
?>

<article id="post-<?php the_ID(); ?>"
	<?php
	post_class( 'post_item_single post_type_page' );
	tint_add_seo_itemprops();
	?>
>

	<?php
	do_action( 'tint_action_before_post_data' );

	tint_add_seo_snippets();

	// Now featured image used as header's background
	// Remove 'false && ' from the condition to display featured image of any page in this place
	if ( false && ! tint_sc_layouts_showed( 'featured' ) && strpos( get_the_content(), '[trx_widget_banner]' ) === false ) {
		do_action( 'tint_action_before_post_featured' );
		tint_show_post_featured();
		do_action( 'tint_action_after_post_featured' );
	}

	do_action( 'tint_action_before_post_content' );
	?>

	<div class="post_content entry-content">
		<?php
			the_content();

			wp_link_pages(
				array(
					'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'tint' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'tint' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				)
			);
			?>
	</div><!-- .entry-content -->

	<?php
	do_action( 'tint_action_after_post_content' );

	do_action( 'tint_action_after_post_data' );
	?>

</article>
