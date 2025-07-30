<?php
/**
 * The template to display the background video in the header
 *
 * @package TINT
 * @since TINT 1.0.14
 */
$tint_header_video = tint_get_header_video();
$tint_embed_video  = '';
if ( ! empty( $tint_header_video ) && ! tint_is_from_uploads( $tint_header_video ) ) {
	if ( tint_is_youtube_url( $tint_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $tint_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php tint_show_layout( tint_get_embed_video( $tint_header_video ) ); ?></div>
		<?php
	}
}
