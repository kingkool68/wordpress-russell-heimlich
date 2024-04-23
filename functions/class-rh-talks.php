<?php
/**
 * Handle everytihng for Talks
 */
class RH_Talks {

	/**
	 * The post type for talks
	 *
	 * @var string
	 */
	public static $post_type = 'talk';

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

	/**
	 * Hook in to WordPress via actions
	 */
	public function setup_actions() {
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'acf/init', array( $this, 'action_acf_init' ) );
		add_action( 'edit_form_after_title', array( $this, 'action_edit_form_after_title' ) );
	}

	/**
	 * Hook in to WordPress via filters
	 */
	public function setup_filters() {
		add_filter( 'body_class', array( $this, 'filter_body_class' ) );
	}

	/**
	 * Register post type
	 */
	public function action_init() {
		wp_register_style(
			'rh-single-talk',
			get_template_directory_uri() . '/assets/css/russell-heimlich--single-talk.min.css',
			$deps  = array( 'rh' ),
			$ver   = null,
			$media = 'all'
		);

		$args = array(
			'label'               => 'talk',
			'description'         => 'Talks',
			'labels'              => RH_Helpers::generate_post_type_labels( 'Talk', 'Talks' ),
			'supports'            => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-megaphone',
			'can_export'          => true,
			'has_archive'         => 'talks',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'rewrite'             => array(
				'slug'       => 'talk',
				'with_front' => false,
			),
		);
		register_post_type( static::$post_type, $args );
	}

	/**
	 * Register Advanced Custom Fields
	 */
	public function action_acf_init() {
		$args = array(
			'key'      => 'talk_fields',
			'title'    => 'Talk Details',
			'fields'   => array(
				array(
					'key'   => 'field_talk_slides_url',
					'name'  => 'talk_slides_url',
					'label' => 'Slides URL',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_talk_slide_source_url',
					'name'  => 'talk_slide_source_url',
					'label' => 'Slide Source URL',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_talk_video_url',
					'name'  => 'talk_video_url',
					'label' => 'Video URL',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_talk_video_embed',
					'name'  => 'talk_video_embed',
					'label' => 'Video Embed',
					'type'  => 'textarea',
				),
				array(
					'key'   => 'field_talk_event_name',
					'name'  => 'talk_event_name',
					'label' => 'Event Name',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_talk_event_url',
					'name'  => 'talk_event_url',
					'label' => 'Event URL',
					'type'  => 'text',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => static::$post_type,
					),
				),
			),
		);
		acf_add_local_field_group( $args );
	}

	/**
	 * Add description label before the main content
	 *
	 * @param  WP_Post $post WP Post object being edited
	 */
	public function action_edit_form_after_title( $post ) {
		if ( $post->post_type !== static::$post_type ) {
			return;
		}
		echo '<h2 style="padding: 2rem 0 0;">Talk Description</h2>';
	}

	/**
	 * Remove `single-talk` from body class
	 *
	 * @param  array $the_class  Body classes to modify
	 *
	 * @return array         Modified body classes
	 */
	public function filter_body_class( $the_class = array() ) {
		$values_to_remove = array( 'single-talk' );
		$the_class        = array_diff( $the_class, $values_to_remove );
		return $the_class;
	}

	/**
	 * Get meta data for a given talk post
	 *
	 * @param  integer|WP_Post $post The WordPress post of the talk to get data for
	 */
	public static function get_data( $post = 0 ) {
		$post        = get_post( $post );
		$video_embed = get_field( 'talk_video_embed', $post->ID );
		$video_url   = get_field( 'talk_video_url', $post->ID );
		if ( empty( $video_embed ) && ! empty( $video_url ) ) {
			$video_embed = wp_oembed_get( $video_url );
		}
		$output = array(
			'slides_url'        => get_field( 'talk_slides_url', $post->ID ),
			'slides_source_url' => get_field( 'talk_slide_source_url', $post->ID ),
			'video_url'         => $video_url,
			'video_embed'       => $video_embed,
			'event_name'        => get_field( 'talk_event_name', $post->ID ),
			'event_url'         => get_field( 'talk_event_url', $post->ID ),
		);
		return (object) $output;
	}
}

RH_Talks::get_instance();
