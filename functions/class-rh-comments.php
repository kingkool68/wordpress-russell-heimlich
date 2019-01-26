<?php

class RH_Comments {

	private $nonce_value = 'rh-comments';
	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
			$instance->setup_actions();
		}
		return $instance;
	}

	public function setup_actions() {
		add_action( 'comment_form', array( $this, 'action_comment_form' ) );
		add_action( 'pre_comment_on_post', array( $this, 'action_pre_comment_on_post' ) );
	}

	public function action_comment_form() {
		wp_nonce_field( $this->nonce_value );
	}

	public function action_pre_comment_on_post() {
		if (
			empty( $_REQUEST['_wpnonce'] ) ||
			! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), $this->nonce_value )
		) {
			wp_die( 'Bad comment form nonce' );
		}
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

	public static function comment_form( $args = array(), $post_id = null ) {
		$defaults = array(
			'format'      => 'html5',
			'title_reply' => '',
		);
		$args     = wp_parse_args( $args, $defaults );
		ob_start();
			comment_form( $args, $post_id );
		return ob_get_clean();
	}
}
RH_Comments::get_instance();
