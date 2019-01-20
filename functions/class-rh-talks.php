<?php
/**
 * Handle anything around general JavaScripts and CSS stylesheets
 */
class RH_Talks {

	public $post_type = 'talk';

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
		add_action( 'save_post_' . $this->post_type, array( $this, 'action_save_post_talk' ), 10, 2 );
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
		register_post_type( $this->post_type, $args );
	}

	/**
	 * Add metaboxes for Talks
	 *
	 * @param string $post_type The post type metaboxes are being added to
	 */
	public function action_add_meta_boxes( $post_type = '' ) {
		if ( $post_type !== $this->post_type ) {
			return;
		}
		add_meta_box( 'rh-talk', 'Talk Details', array( $this, 'handle_talk_metabox' ), $post_type, 'advanced', 'low' );
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

		if ( ! empty( $_REQUEST['rh_talk_nonce'] ) && wp_verify_nonce( $_REQUEST['rh_talk_nonce'], $this->post_type ) ) {
			update_post_meta( $post_id, 'talk-meta', (array) $_REQUEST['api_indicator'] );
		}
	}

	public function handle_talk_metabox() {
		$context = array(
			'val'   => '',
			'nonce' => wp_create_nonce( $this->post_type ),
		);
		Sprig::out( 'admin/talk-meta-box.twig', $context );
	}
}

RH_Talks::get_instance();
