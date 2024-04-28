<?php
/**
 * Tease a talk with this handy talk teaser block
 */
class RH_Talk_Teaser_Block {

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

	/**
	 * Hook into WordPress via actions
	 */
	public function setup_actions() {
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'acf/init', array( $this, 'action_acf_init' ) );
	}

	/**
	 * Register block-speciifc styles and scripts
	 */
	public function action_init() {
		wp_register_style(
			'rh-talk-teaser-block',
			get_template_directory_uri() . '/assets/css/talk-teaser-block/talk-teaser-block.min.css',
			$deps  = array( 'rh' ),
			$ver   = null,
			$media = 'all'
		);
	}

	/**
	 * Register Advanced Custom Fields
	 */
	public function action_acf_init() {

		// Custom fields for the block
		$args = array(
			'name'            => 'rh-talk-teaser',
			'title'           => 'RH Talk Teaser',
			'description'     => 'A custom Talk Teaser block . ',
			'render_callback' => array( $this, 'render_from_block' ),
			'category'        => 'rh',
			'icon'            => 'megaphone',
			'keywords'        => array( 'talk', 'tease' ),
			'enqueue_assets'  => function () {
				wp_enqueue_style( 'rh-talk-teaser-block' );
			},

		);
		acf_register_block_type( $args );

		$args = array(
			'key'      => 'talk_teaser_block_fields',
			'title'    => 'Talk Teaser Block Fields',
			'fields'   => array(
				array(
					'key'   => 'field_talk_teaser_block_image_id',
					'name'  => 'talk_teaser_block_image_id',
					'label' => 'Image',
					'type'  => 'text',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => ' == ',
						'value'    => 'acf/rh-talk-teaser',
					),
				),
			),
		);
		acf_add_local_field_group( $args );
	}

	/**
	 * Render a Text Image component
	 *
	 * @param array $args Arguments to modify what is rendered
	 */
	public static function render( $args = array() ) {
		$defaults = array(
			'attributes'             => array(),
			'additional_css_classes' => '',
			'the_url'                => '',
			'the_title'              => '',
			'the_image'              => '',
			'the_event_name'         => '',
			'the_date'               => '',
			'the_display_date'       => '',
			'the_machine_date'       => '',
			'the_excerpt'            => '',
		);
		$context  = RH_Blocks::do_context( $args, $defaults );

		if ( ! empty( $context['the_date'] ) ) {
			$date_values                 = RH_Helpers::get_date_values( $context['the_date'] );
			$context['the_display_date'] = $date_values->display_date;
			$context['the_machine_date'] = $date_values->machine_date;
		}

		$context['the_title']   = apply_filters( 'the_title', $context['the_title'] );
		$context['the_excerpt'] = apply_filters( 'the_content', $context['the_excerpt'] );

		wp_enqueue_style( 'rh-talk-teaser-block' );
		return Sprig::render( 'talk-teaser-block.twig', $context );
	}

	/**
	 * Block callback function
	 *
	 * @param   array        $block The block settings and attributes.
	 * @param   string       $content The block inner HTML (empty).
	 * @param   bool         $is_preview True during AJAX preview.
	 * @param   (int|string) $post_id The post ID this block is saved to.
	 */
	public function render_from_block( $block = array(), $content = '', $is_preview = false, $post_id = 0 ) {
		$additional = RH_Blocks::get_attributes_from_block( $block );

		$args = array(
			'attributes'             => $additional->attributes,
			'additional_css_classes' => $additional->css_class,
		);
		echo static::render( $args );
	}

	/**
	 * Render from a post
	 *
	 * @param  integer|WP_Post $post The WordPress post to get data for
	 * @param  array           $args Arguments to modify what is rendered
	 */
	public static function render_from_post( $post = 0, $args = array() ) {
		$post     = get_post( $post );
		$data     = RH_Talks::get_data( $post );
		$defaults = array(
			'attributes'             => array(),
			'additional_css_classes' => '',
			'the_url'                => get_permalink( $post ),
			'the_title'              => $post->post_title,
			'the_image'              => RH_Media::render_image_from_post( $post ),
			'the_event_name'         => $data->event_name,
			'the_date'               => $post->post_date,
			'the_excerpt'            => $post->post_excerpt,
		);
		$args     = wp_parse_args( $args, $defaults );
		return static::render( $args );
	}

	/**
	 * Render a series of components from a WP_Query
	 *
	 * @param  WP_Query $the_query The WP_Query to loop over and get posts
	 * @param  array    $args Arguments to modify what is rendered
	 */
	public static function render_from_wp_query( $the_query = false, $args = array() ) {
		global $wp_query;
		if ( ! $the_query ) {
			$the_query = $wp_query;
		}
		if ( ! $the_query instanceof WP_Query ) {
			throw new Exception( '$the_query is not a WP_Query object!' );
		}
		$output = array();
		if ( ! empty( $the_query->posts ) ) {
			foreach ( $the_query->posts as $the_post ) {
				$output[] = static::render_from_post( $the_post, $args );
			}
		}
		return $output;
	}
}
RH_Talk_Teaser_Block::get_instance();
