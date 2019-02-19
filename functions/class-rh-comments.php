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


	public static function render_comments_section( $args = array() ) {
		$defaults = array(
			'comments_count' => 0,
			'comment_label'  => 'comments',
			'comments_list'  => '',
			'comment_form'   => '',
		);
		$args     = wp_parse_args( $args, $defaults );
		$context  = array(
			'comments_count' => $args['comments_count'],
			'comment_label'  => ucfirst( $args['comment_label'] ),
			'comments_list'  => $args['comments_list'],
			'comment_form'   => $args['comment_form'],
		);
		return Sprig::render( 'comments-section.twig', $context );
	}

	public static function render_comments_section_by_post( $post_id = null ) {
		$post          = get_post( $post_id );
		$comment_count = static::get_comment_count_by_post( $post->ID );
		$args = array(
			'comments_count' => $comment_count->number,
			'comment_label'  => $comment_count->label,
			'comments_list'  => static::get_comments(),
			'comment_form'   => static::get_comment_form( array(), $post->ID ),
		);
		return static::render_comments_section( $args );
	}

	public static function get_comments( $args = array(), $comments = array() ) {
		$defaults = array(
			'style'    => 'ol',
			'type'     => 'comment',
			'callback' => array( __CLASS__, 'start_el' ),
			'echo'     => false,
			'post_id'  => get_the_ID(),
		);
		$args     = wp_parse_args( $args, $defaults );

		if ( empty( $comments ) ) {
			$comments = get_comments( array(
				'post_id' => $args['post_id'],
				'orderby' => 'comment_date_gmt',
				'order'   => 'ASC',
				'status'  => 'approve',
			) );
		}

		return wp_list_comments( $args, $comments );
	}

	public static function get_comment_form( $args = array(), $post_id = null ) {
		$defaults = array(
			'format'      => 'html5',
			'title_reply' => '',
		);
		$args     = wp_parse_args( $args, $defaults );
		ob_start();
			comment_form( $args, $post_id );
		return ob_get_clean();
	}

	public static function get_comment_count( $comment_count = 0 ) {
		return (object) array(
			'number' => $comment_count,
			'label'  => _n( 'comment', 'comments', $comment_count ),
		);
	}

	public static function get_comment_count_by_post( $post_id = null ) {
		$comment_count = get_comments_number( $post_id );
		return static::get_comment_count( $comment_count );
	}

	public static function make_stub_comment_object( $args = array() ) {
		$defaults    = array(
			'comment_ID'           => '1234',
			'comment_post_ID'      => '0',
			'comment_author'       => 'Comment Author',
			'comment_author_email' => 'example@example.com',
			'comment_author_url'   => 'https://example.com',
			'comment_author_IP'    => '1.1.1.1',
			'comment_date'         => date( 'Y-m-d H:i:s' ),
			'comment_date_gmt'     => date( 'Y-m-d H:i:s' ),
			'comment_content'      => 'Hello world!',
			'comment_karma'        => '0',
			'comment_approved'     => '1',
			'comment_agent'        => 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_1_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9B206 Safari/7534.48.3',
			'comment_type'         => '',
			'comment_parent'       => '0',
			'user_id'              => 0,
		);
		$args        = wp_parse_args( $args, $defaults );
		$new_comment = new WP_Comment();
		foreach ( $args as $key => $val ) {
			$new_comment->{$key} = $val;
		}
		return $new_comment;
	}
}
RH_Comments::get_instance();
