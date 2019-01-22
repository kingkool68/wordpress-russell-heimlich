<?php
/**
 * Handle anything around general JavaScripts and CSS stylesheets
 */
class RH_Talks {

	public static $post_type = 'talk';

	public static $post_meta_key = 'rh-talk-details';

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
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'add_meta_boxes', array( $this, 'action_add_meta_boxes' ) );
		add_action( 'save_post_' . static::$post_type, array( $this, 'action_save_post_talk' ), 10, 2 );
		add_action( 'edit_form_after_title', array( $this, 'action_edit_form_after_title' ) );
	}

	public function setup_filters() {

	}

	public function action_init() {
		$args = array(
			'label'               => 'talk',
			'description'         => 'Talks',
			'labels'              => RH_Helpers::generate_post_type_labels( 'Talk', 'Talks' ),
			'supports'            => array( 'title', 'editor', 'excerpt', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-megaphone',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( static::$post_type, $args );
	}

	/**
	 * Add metaboxes for Talks
	 *
	 * @param string $post_type The post type metaboxes are being added to
	 */
	public function action_add_meta_boxes( $post_type = '' ) {
		if ( $post_type !== static::$post_type ) {
			return;
		}
		add_meta_box( 'rh-talk-details', 'Talk Details', array( $this, 'handle_talk_details_metabox' ), $post_type, 'advanced', 'low' );
	}

	/**
	 * Saving meta data from metaboxes
	 *
	 * @param  integer $post_id ID of the post being saved
	 */
	public function action_save_post_talk( $post_id = 0 ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $post_id;
		}

		// Detects if the save action is coming from a quick edit/batch edit.
		if ( empty( $_SERVER['REQUEST_URI'] ) || preg_match( '/\edit\.php/', wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) {
			return $post_id;
		}

		if ( ! empty( $_REQUEST['rh-talk-details-nonce'] ) && wp_verify_nonce( $_REQUEST['rh-talk-details-nonce'], static::$post_type ) ) {
			update_post_meta( $post_id, static::$post_meta_key, (array) $_REQUEST['rh-talk-details'] );
		}
	}

	public function action_edit_form_after_title( $post ) {
		if ( $post->post_type !== static::$post_type ) {
			return;
		}
		echo '<h2 style="padding: 24px 0 0;">Talk Description</h2>';
	}

	public function handle_talk_details_metabox( $post ) {
		$data    = static::get_data( $post );
		$context = array(
			'slides_url'        => $data['slides-url'],
			'slides_source_url' => $data['slides-source-url'],
			'video_url'         => $data['video-url'],
			'video_embed'       => $data['video-embed'],
			'event_name'        => $data['event-name'],
			'event_url'         => $data['event-url'],
			'nonce'             => wp_create_nonce( static::$post_type ),
		);
		Sprig::out( 'admin/talk-details-meta-box.twig', $context );
	}

	public static function get_data( $key = '', $post = 0 ) {
		$post      = get_post( $post );
		$post_meta = get_post_meta( $post->ID, static::$post_meta_key, true );
		$output    = array(
			'slides-url'        => '',
			'slides-source-url' => '',
			'video-url'         => '',
			'video-embed'       => '',
			'event-name'        => '',
			'event-url'         => '',
		);
		foreach ( $output as $key => $val ) {
			if ( isset( $post_meta[ $key ] ) ) {
				$output[ $key ] = $post_meta[ $key ];
			}
		}
		return $output;
	}

	public static function render_archive_item( $args = array() ) {
		$defaults           = array(
			'url'          => '',
			'title'        => '',
			'excerpt'      => '',
			'date'         => '',
			'display_date' => '',
			'machine_date' => '',
			'event_name'   => '',
		);
		$context            = wp_parse_args( $args, $defaults );
		$context['title']   = apply_filters( 'the_title', $context['title'] );
		$context['excerpt'] = apply_filters( 'the_excerpt', $context['excerpt'] );

		if ( ! empty( $context['date'] ) ) {
			$date = strtotime( $context['date'] );
			if ( empty( $context['machine_date'] ) ) {
				$context['machine_date'] = date( DATE_W3C, $date );
			}
			if ( empty( $context['display_date'] ) ) {
				$context['display_date'] = date( get_option( 'date_format' ), $date );
			}
		}

		return Sprig::render( 'talk-archive-item.twig', $context );
	}

	public static function render_archive_item_from_post( $post, $args = array() ) {
		$post = get_post( $post );
		$data = static::get_data( $post->ID );
		$args = array(
			'url'        => get_permalink( $post ),
			'title'      => get_the_title( $post ),
			'excerpt'    => get_the_excerpt( $post ),
			'date'       => get_the_date( '', $post ),
			'event_name' => $data['event-name'],
		);
		return static::render_archive_item( $args );
	}
}

RH_Talks::get_instance();