<?php
/**
 * The template to display the team member's page
 *
 * @package ThemeREX Addons
 * @since v1.2
 */

global $TRX_ADDONS_STORAGE;

get_header();

while ( have_posts() ) { the_post();
	
	do_action('trx_addons_action_before_article', 'team.single');

	$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
	$meta_box = trx_addons_meta_box_get(get_post_type());

	$need_image    = ! trx_addons_sc_layouts_showed('featured') && has_post_thumbnail();
	$need_title    = ! trx_addons_sc_layouts_showed('title');
	$need_subtitle = ! empty( $meta['subtitle'] );
	$need_details  = false;
	if ( is_array( $meta_box ) ) {
		foreach ($meta_box as $k=>$v) {
			if ( ! empty($v['details']) && ! empty($meta[$k]) ) {
				$need_details = true;
				break;
			}
		}
	}
	$need_info     =  ! empty( $meta['brief_info'] );
	$need_socials  =  ! empty( $meta['socials'][0]['url'] );

	?><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>" <?php post_class( 'team_member_page itemscope' ); trx_addons_seo_snippets('', 'Article'); ?>><?php
	
		do_action('trx_addons_action_article_start', 'team.single');


		if ( $need_title || $need_subtitle ) {
			?>
			<div class="team_member_description"><?php
				if ($need_subtitle) {
					?>
					<h3 class="team_member_position"><?php echo esc_html($meta['subtitle']); ?></h3>
					<?php
				}
				if ($need_title) {
					?><h2 class="team_member_title"><?php the_title(); ?></h2><?php
				}
			?></div><?php
		}

		// Post header
		if ( $need_image || $need_socials ) {

				// Image
				if ( $need_image ) {
					?><div class="team_member_featured">
						<div class="team_member_avatar post_featured">
							<?php
							do_action('trx_addons_action_before_featured');
							the_post_thumbnail(
												apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size('masonry-big'), 'team-single'),
												trx_addons_seo_image_params(array(
																					'alt' => get_the_title()
																					))
												);
							do_action('trx_addons_action_after_featured');
							?>
						</div>

						<div class="team_member_description_socials"><?php
							if ( $need_socials ) {
								?><div class="team_member_socials socials_wrap"><?php trx_addons_show_layout(trx_addons_get_socials_links_custom($meta['socials'])); ?></div><?php
							}
						?></div>

					</div>
					<?php
				}
		}

		// Post content
		if ( trim( get_the_content() ) != '' || ( function_exists('trx_addons_elm_is_preview') && trx_addons_elm_is_preview() ) ) {
			?><div class="team_member_content team_member_page_content entry-content"<?php trx_addons_seo_snippets('articleBody'); ?>><?php
				the_content( );
			?></div><!-- .entry-content --><?php
		}

		do_action('trx_addons_action_article_end', 'team.single');

	?></article><?php

	$tint_cpt_team_article_info_output = '';
	ob_start();

	if ( $need_details || $need_info || ($need_socials && !$need_image)) {
		?>
		<div class="team_member_details">
			<?php
			if ( $need_info ) {
				?>
				<div class="team_member_brief_info">
					<h3 class="section_title team_member_brief_info_title"><?php esc_html_e('Biography', 'tint'); ?></h3>
					<div class="team_member_brief_info_text"><?php echo wpautop($meta['brief_info']); ?></div>
				</div>
				<?php
			}

			if ( $need_details && is_array( $meta_box ) ) { ?>
				<div class="team_member_brief_info_details"><?php
					foreach ($meta_box as $k=>$v) {
						if (!empty($v['details']) && !empty($meta[$k])) {
							?><div class="team_member_details_<?php echo esc_attr($k); ?>">
							<span class="team_member_details_label"><?php
								echo esc_html($v['title']); ?>:
												</span><span class="team_member_details_value"><?php
								trx_addons_show_value($meta[$k], $v['type']);
								?></span>
							</div><?php
						}
					} ?>
				</div>
			<?php
			}

			if ( $need_socials && !$need_image ) { ?>
				<div class="team_member_description_socials">
					<div class="team_member_socials socials_wrap"><?php trx_addons_show_layout(trx_addons_get_socials_links_custom($meta['socials'])); ?></div>
				</div><?php
			}
			?>
		</div>
		<?php
	}
	$tint_cpt_team_article_info_output = ob_get_contents();
	ob_end_clean();

	do_action('trx_addons_action_after_article', 'team.single', $tint_cpt_team_article_info_output);

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

get_footer();
