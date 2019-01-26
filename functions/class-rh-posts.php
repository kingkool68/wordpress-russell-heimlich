<?php
/**
 * Handle everything for Posts
 */
class RH_Posts {

	public static $post_type = 'post';

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
			$instance->setup_actions();
			$instance->setup_filters();
		}
		return $instance;
	}

	public function setup_actions() {
		add_action( 'rh/the_loop_' . static::$post_type, array( $this, 'action_rh_the_loop' ), 10, 2 );
	}

	public function setup_filters() {

	}

	public function action_rh_the_loop( $post, $index ) {
		echo static::render_archive_item_from_post( $post );
	}

	public static function render_archive_item( $args = array() ) {
		$defaults         = array(
			'url'          => '',
			'title'        => '',
			'date'         => '',
			'display_date' => '',
			'machine_date' => '',
		);
		$context          = wp_parse_args( $args, $defaults );
		$context['title'] = apply_filters( 'the_title', $context['title'] );

		if ( ! empty( $context['date'] ) ) {
			$dates = RH_Helpers::get_date_values( $context['date'] );
			if ( empty( $context['machine_date'] ) ) {
				$context['machine_date'] = $dates->machine_date;
			}
			if ( empty( $context['display_date'] ) ) {
				$context['display_date'] = $dates->display_date;
			}
		}

		return Sprig::render( 'post-archive-item.twig', $context );
	}

	public static function render_archive_item_from_post( $post, $args = array() ) {
		$post = get_post( $post );
		$args = array(
			'url'   => get_permalink( $post ),
			'title' => get_the_title( $post ),
			'date'  => $post->post_date,
		);
		return static::render_archive_item( $args );
	}

	/**
	 * Render an archive item from a WP_Query object
	 *
	 * @param  object $the_query A WP_Query object
	 * @return string            HTML of all archive items
	 * @throws Exception         If $the_query isn't a WP_Query object then bail
	 */
	public static function render_archive_items_from_wp_query( $the_query = false ) {
		global $wp_query;
		if ( ! $the_query ) {
			$the_query = $wp_query;
		}
		if ( ! $the_query instanceof WP_Query ) {
			throw new Exception( '$the_query is not a WP_Query object!' );
		}

		$output = [];
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			$output[] = static::render_archive_item_from_post( $post );
		endwhile;
		wp_reset_postdata();
		return implode( "\n", $output );
	}
}

RH_Posts::get_instance();
