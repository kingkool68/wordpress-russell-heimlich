<?php

class RH_Comments {

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
		}
		return $instance;
	}

	public static function get_comments( $args = array(), $comments = array() ) {
		$defaults = array(
			'style'    => 'ol',
			'type'     => 'comment',
			'callback' => array( __CLASS__, 'start_el' ),
			'echo'     => false,
		);
		$args     = wp_parse_args( $args, $defaults );

		if ( empty( $comments ) ) {
			$comments = get_comments( array(
				'post_id' => get_the_ID(),
				'orderby' => 'comment_date_gmt',
				'order'   => 'ASC',
				'status'  => 'approve',
			) );
		}

		return wp_list_comments( $args, $comments );
	}

	/**
	 * Starts the comment element
	 * Meant to be used as a callback for wp_list_comments
	 *
	 * @param  WP_Comment $comment Comment data object
	 * @param  array      $args    An array of arguments
	 * @param  int        $depth   Depth of the current comment in reference to parents
	 */
	public static function start_el( $comment, $args = array(), $depth = 1 ) {
		$tag = 'li';
		if ( 'div' === $args['style'] ) {
			$tag = 'div';
		}

		$comment_class      = implode( ' ', get_comment_class() );
		$comment_class_attr = 'class="' . esc_attr( $comment_class ) . '"';

		$context = array(
			'tag'           => $tag,
			'id'            => $comment->comment_ID,
			'class_attr'    => $comment_class_attr,
			'text'          => apply_filters( 'comment_text', get_comment_text() ),
			'author_link'   => get_comment_author_link( $comment ),
			'permalink_url' => get_comment_link( $comment, $args ),
			'machine_date'  => get_comment_time( 'c' ),
			'date'          => get_comment_date( '', $comment ),
			'time'          => get_comment_time(),
			'avatar'        => get_avatar( $comment, $args['avatar_size'] ),
			'approved'      => $comment->comment_approved,
			'reply_link'    => get_comment_reply_link( array(
				'before' => '<div class="reply">',
				'after'  => '</div>',
			), $comment ),
		);
		Sprig::out( 'comment.twig', $context );
	}
}
RH_Comments::get_instance();
