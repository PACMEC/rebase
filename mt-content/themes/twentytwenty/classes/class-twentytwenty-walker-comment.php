<?php
/**
 * Custom comment walker for this theme.
 *
 * @package paCMec
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

if ( ! class_exists( 'TwentyTwenty_Walker_Comment' ) ) {
	/**
	 * CUSTOM COMMENT WALKER
	 * A custom walker for comments, based on the walker in Twenty Nineteen.
	 */
	class TwentyTwenty_Walker_Comment extends Walker_Comment {

		/**
		 * Outputs a comment in the HTML5 format.
		 *
		 * @see mt_list_comments()
		 * @see https://developer.managertechnology.com.co/pacmec/reference/functions/get_comment_author_url/
		 * @see https://developer.managertechnology.com.co/pacmec/reference/functions/get_comment_author/
		 * @see https://developer.managertechnology.com.co/pacmec/reference/functions/get_avatar/
		 * @see https://developer.managertechnology.com.co/pacmec/reference/functions/get_comment_reply_link/
		 * @see https://developer.managertechnology.com.co/pacmec/reference/functions/get_edit_comment_link/
		 *
		 * @param MT_Comment $comment Comment to display.
		 * @param int        $depth   Depth of the current comment.
		 * @param array      $args    An array of arguments.
		 */
		protected function html5_comment( $comment, $depth, $args ) {

			$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

			?>
			<<?php echo $tag; // phpcs:ignore paCMec.Security.EscapeOutput.OutputNotEscaped -- static output ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
				<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
					<footer class="comment-meta">
						<div class="comment-author vcard">
							<?php
							$comment_author_url = get_comment_author_url( $comment );
							$comment_author     = get_comment_author( $comment );
							$avatar             = get_avatar( $comment, $args['avatar_size'] );
							if ( 0 !== $args['avatar_size'] ) {
								if ( empty( $comment_author_url ) ) {
									echo mt_kses_post( $avatar );
								} else {
									printf( '<a href="%s" rel="external nofollow" class="url">', $comment_author_url ); // phpcs:ignore paCMec.Security.EscapeOutput.OutputNotEscaped --Escaped in https://developer.managertechnology.com.co/pacmec/reference/functions/get_comment_author_url/
									echo mt_kses_post( $avatar );
								}
							}

							printf(
								'<span class="fn">%1$s</span><span class="screen-reader-text says">%2$s</span>',
								esc_html( $comment_author ),
								__( 'says:', 'twentytwenty' )
							);

							if ( ! empty( $comment_author_url ) ) {
								echo '</a>';
							}
							?>
						</div><!-- .comment-author -->

						<div class="comment-metadata">
							<?php
							/* translators: 1: Comment date, 2: Comment time. */
							$comment_timestamp = sprintf( __( '%1$s at %2$s', 'twentytwenty' ), get_comment_date( '', $comment ), get_comment_time() );

							printf(
								'<a href="%s"><time datetime="%s" title="%s">%s</time></a>',
								esc_url( get_comment_link( $comment, $args ) ),
								get_comment_time( 'c' ),
								esc_attr( $comment_timestamp ),
								esc_html( $comment_timestamp )
							);

							if ( get_edit_comment_link() ) {
								printf(
									' <span aria-hidden="true">&bull;</span> <a class="comment-edit-link" href="%s">%s</a>',
									esc_url( get_edit_comment_link() ),
									__( 'Edit', 'twentytwenty' )
								);
							}
							?>
						</div><!-- .comment-metadata -->

					</footer><!-- .comment-meta -->

					<div class="comment-content entry-content">

						<?php

						comment_text();

						if ( '0' === $comment->comment_approved ) {
							?>
							<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwenty' ); ?></p>
							<?php
						}

						?>

					</div><!-- .comment-content -->

					<?php

					$comment_reply_link = get_comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<span class="comment-reply">',
								'after'     => '</span>',
							)
						)
					);

					$by_post_author = twentytwenty_is_comment_by_post_author( $comment );

					if ( $comment_reply_link || $by_post_author ) {
						?>

						<footer class="comment-footer-meta">

							<?php
							if ( $comment_reply_link ) {
								echo $comment_reply_link; // phpcs:ignore paCMec.Security.EscapeOutput.OutputNotEscaped -- Link is escaped in https://developer.managertechnology.com.co/pacmec/reference/functions/get_comment_reply_link/
							}
							if ( $by_post_author ) {
								echo '<span class="by-post-author">' . __( 'By Post Author', 'twentytwenty' ) . '</span>';
							}
							?>

						</footer>

						<?php
					}
					?>

				</article><!-- .comment-body -->

			<?php
		}
	}
}