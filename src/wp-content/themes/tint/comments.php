<?php
/**
 * The template to display the Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'tint_output_single_comment' ) ) {
	/**
	 * Callback for output a single comment layout in the list of comment
	 * depends of the comment type.
	 *
	 * @param object  $comment  A current comment object.
	 * @param array   $args     Arguments to display layout.
	 * @param int     $depth    A current depth of the comment.
	 */
	function tint_output_single_comment( $comment, $args, $depth ) {
		switch ( $comment->comment_type ) {
			case 'pingback':
				?>
				<li class="trackback"><?php esc_html_e( 'Trackback:', 'tint' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'tint' ), '<span class="edit-link">', '<span>' ); ?>
				<?php
				break;
			case 'trackback':
				?>
				<li class="pingback"><?php esc_html_e( 'Pingback:', 'tint' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'tint' ), '<span class="edit-link">', '<span>' ); ?>
				<?php
				break;
			default:
				$author_id   = $comment->user_id;
				$author_link = ! empty( $author_id ) && user_can( $author_id, 'edit_posts' ) ? get_author_posts_url( $author_id ) : '';
				$author_post = get_the_author_meta( 'ID' ) == $author_id;
				$mult        = tint_get_retina_multiplier();
				$comment_id  = get_comment_ID();
				?>
				<li id="comment-<?php echo esc_attr( $comment_id ); ?>" <?php comment_class( 'comment_item' ); ?>>
					<div id="comment_body-<?php echo esc_attr( $comment_id ); ?>" class="comment_body">
						<div class="comment_author_avatar"><?php echo get_avatar( $comment, 90 * $mult ); ?></div>
						<div class="comment_content">
							<div class="comment_info">
								<?php if ( $author_post ) { ?>
									<div class="comment_bypostauthor">
										<?php
										esc_html_e( 'Post Author', 'tint' );
										?>
									</div>
								<?php } ?>
								<h6 class="comment_author">
								<?php
									echo ( ! empty( $author_link ) ? '<a href="' . esc_url( $author_link ) . '">' : '' )
											. esc_html( get_comment_author() )
											. ( ! empty( $author_link ) ? '</a>' : '' );
								?>
								</h6>
								<div class="comment_posted">
									<span class="comment_posted_label"><?php esc_html_e( 'Posted', 'tint' ); ?></span>
									<span class="comment_date">
									<?php
										echo esc_html( get_comment_date( get_option( 'date_format' ) ) );
									?>
									</span>
									<span class="comment_time_label"><?php esc_html_e( 'at', 'tint' ); ?></span>
									<span class="comment_time">
									<?php
										echo esc_html( get_comment_date( get_option( 'time_format' ) ) );
									?>
									</span>
								</div>
								<?php
								// Show rating in the comment
								do_action( 'trx_addons_action_post_rating', 'c' . esc_attr( $comment_id ) );
								?>
							</div>
							<div class="comment_text_wrap">
								<?php if ( 0 === $comment->comment_approved ) { ?>
								<div class="comment_not_approved"><?php esc_html_e( 'Your comment is awaiting moderation.', 'tint' ); ?></div>
								<?php } ?>
								<div class="comment_text"><?php comment_text(); ?></div>
							</div>
							<div class="comment_footer">
								<?php
								if ( 1 == $comment->comment_approved && tint_exists_trx_addons() ) {
									?>
									<span class="comment_counters"><?php tint_show_comment_counters('likes,rating'); ?></span>
									<?php
								}
								$args['max_depth'] = apply_filters( 'tint_filter_comment_depth', $args['max_depth'] );
								if ( $depth < $args['max_depth'] ) {
									?>
									<span class="reply comment_reply">
										<?php
										comment_reply_link(
											array_merge(
												$args, array(
													'add_below' => 'comment_body',
													'depth' => $depth,
													'max_depth' => $args['max_depth'],
												)
											)
										);
										?>
									</span>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				<?php
				break;
		}
	}
}


// Display a list of comments
if ( ! tint_sc_layouts_showed( 'comments' ) && ( have_comments() || comments_open() ) ) {
	
	tint_sc_layouts_showed( 'comments', true );

	$tint_full_post_loading = tint_get_value_gp( 'action' ) == 'full_post_loading';
	$tint_posts_navigation  = tint_get_theme_option( 'posts_navigation' );
	$tint_comments_number   = get_comments_number();
	$tint_show_comments     = tint_get_value_gp( 'show_comments' ) == 1
									|| ( ! $tint_full_post_loading
											&&
											( 'scroll' != $tint_posts_navigation
												|| tint_get_theme_option( 'posts_navigation_scroll_hide_comments', 1 ) == 0
												|| tint_check_url( '#comments' )
											)
										);
	$tint_show_button       = ! $tint_show_comments || (int)tint_get_theme_option( 'show_comments_button', 0 ) == 1;
	$tint_open_comments     = tint_get_value_gp( 'show_comments' ) == 1
									|| ! $tint_show_button
									|| tint_get_theme_option( 'show_comments', 'hidden' ) == 'visible'
									|| tint_check_url( '#comments' );

	$tint_msg_show          = $tint_comments_number > 0
									? wp_kses_data( sprintf( _n( 'Show comment', 'Show comments (%d)', $tint_comments_number, 'tint' ), $tint_comments_number ) )
									: wp_kses_data( __( 'Leave a comment', 'tint' ) );
	$tint_msg_hide          = wp_kses_data( __( 'Hide comments', 'tint' ) );

	do_action( 'tint_action_before_comments' );

	if ( $tint_show_button ) {
		?>
		<div class="show_comments_single">
			<a href="<?php
				if ( $tint_show_comments ) {
					echo '#';
				} else {
					echo esc_url( add_query_arg( array( 'show_comments' => 1 ), get_comments_link() ) );
				}
			?>"
			class="<?php
				echo apply_filters( 'tint_filter_comments_button_class', 'show_comments_button' );
				if ( $tint_show_comments && $tint_open_comments ) {
					echo ' opened';
				}
			?>"
			data-show="<?php echo esc_attr( $tint_msg_show ); ?>"
			data-hide="<?php echo esc_attr( $tint_msg_hide ); ?>"
			>
				<?php
				if ( $tint_show_comments && $tint_open_comments ) {
					echo esc_html( $tint_msg_hide );
				} else {
					echo esc_html( $tint_msg_show );
				}
				?>
			</a>
		</div>
		<?php
	}

	if ( $tint_show_comments ) {
		?>
		<section class="comments_wrap<?php if ( $tint_open_comments ) { echo ' opened'; } ?>">
			<?php
			if ( have_comments() ) {
				?>
				<div id="comments" class="comments_list_wrap">
					<h3 class="section_title comments_list_title">
					<?php
					$tint_post_comments = get_comments_number();
					echo esc_html( $tint_post_comments );
					?>
				<?php echo esc_html( _nx( 'Comment', 'Comments', $tint_post_comments, 'Number of comments', 'tint' ) ); ?></h3>
					<ul class="comments_list">
						<?php
						wp_list_comments( array( 'callback' => 'tint_output_single_comment' ) );
						?>
					</ul>
						<?php
						if ( ! comments_open() && get_comments_number() != 0 && post_type_supports( get_post_type(), 'comments' ) ) {
							?>
						<p class="comments_closed"><?php esc_html_e( 'Comments are closed.', 'tint' ); ?></p>
							<?php
						}
						if ( get_comment_pages_count() > 1 ) {
							?>
						<div class="comments_pagination"><?php paginate_comments_links(); ?></div>
							<?php
						}
						?>
				</div>
					<?php
			}

			if ( comments_open() ) {
				?>
				<div class="comments_form_wrap">
					<div class="comments_form">
					<?php
					$tint_form_style = esc_attr( tint_get_theme_option( 'input_hover', 'default' ) );
					if ( empty( $tint_form_style ) || tint_is_inherit( $tint_form_style ) ) {
						$tint_form_style = 'default';
					}
					$tint_commenter     = wp_get_current_commenter();
					$tint_req           = get_option( 'require_name_email' );
					$tint_comments_args = apply_filters(
						'tint_filter_comment_form_args', array(
							// class of the 'form' tag
							'class_form'           => 'comment-form ' . ( 'default' != $tint_form_style ? 'sc_input_hover_' . esc_attr( $tint_form_style ) : '' ),
							// change the id of send button
							'id_submit'            => 'send_comment',
							// change the title of send button
							'label_submit'         => esc_html__( 'Leave a comment', 'tint' ),
							// change the title of the reply section
							'title_reply'          => esc_html__( 'Leave a comment', 'tint' ),
							'title_reply_before'   => '<h3 id="reply-title" class="section_title comments_form_title comment-reply-title">',
							'title_reply_after'    => '</h3>',
							// remove "Logged in as"
							'logged_in_as'         => '',
							// remove text before textarea
							'comment_notes_before' => '',
							// remove text after textarea
							'comment_notes_after'  => '',
							'fields'               => array(
								'author' => tint_single_comments_field(
									array(
										'form_style'        => $tint_form_style,
										'field_type'        => 'text',
										'field_req'         => $tint_req,
										'field_icon'        => 'icon-user',
										'field_value'       => isset( $tint_commenter['comment_author'] ) ? $tint_commenter['comment_author'] : '',
										'field_name'        => 'author',
										'field_title'       => esc_html__( 'Name', 'tint' ),
										'field_placeholder' => esc_attr__( 'Your Name', 'tint' ),
									)
								),
								'email'  => tint_single_comments_field(
									array(
										'form_style'        => $tint_form_style,
										'field_type'        => 'text',
										'field_req'         => $tint_req,
										'field_icon'        => 'icon-mail',
										'field_value'       => isset( $tint_commenter['comment_author_email'] ) ? $tint_commenter['comment_author_email'] : '',
										'field_name'        => 'email',
										'field_title'       => esc_html__( 'E-mail', 'tint' ),
										'field_placeholder' => esc_attr__( 'Your E-mail', 'tint' ),
									)
								),
							),
							// redefine your own textarea (the comment body)
							'comment_field'        => tint_single_comments_field(
								array(
									'form_style'        => $tint_form_style,
									'field_type'        => 'textarea',
									'field_req'         => true,
									'field_icon'        => 'icon-feather',
									'field_value'       => '',
									'field_name'        => 'comment',
									'field_title'       => esc_html( _x( 'Comment', 'Field title', 'tint' ) ),
									'field_placeholder' => esc_attr__( 'Your comment', 'tint' ),
								)
							),
						)
					);
					comment_form( $tint_comments_args );
					?>
					</div>
				</div>
				<?php
			}
			?>
		</section>
		<?php
		do_action( 'tint_action_after_comments' );
	}
}
