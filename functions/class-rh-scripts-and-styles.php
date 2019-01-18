<?php
/**
 * Handle anything around general JavaScripts and CSS stylesheets
 */
class RH_Scripts_And_Styles {

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
	 * Hook into various WordPress actions
	 */
	public function setup_actions() {
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ) );
	}

	/**
	 * Register various scripts and stylesheets
	 */
	public function action_init() {
		wp_register_style(
			'russell-heimlich',
			get_template_directory_uri() . '/assets/css/russell-heimlich' . self::get_css_suffix(),
			array(),
			null,
			'all'
		);
	}

	/**
	 * Enqueue the main stylesheet at the right time
	 * NOTE: This needs to happen later than init hook otherwise WordPress admin css doesn't load
	 */
	public function action_wp_enqueue_scripts() {
		wp_enqueue_style( 'russell-heimlich' );
	}

	/**
	 * Get the CSS suffix depending on the environment
	 *
	 * Production should use *.min.css
	 * Local development should use *.css which includes sourcemaps
	 *
	 * @return string CSS file suffix
	 */
	public static function get_css_suffix() {
		if ( WP_DEBUG ) {
			return '.css';
		}
		return '.min.css';
	}

	/**
	 * Get the JavaScript suffix depending on the environment
	 *
	 * Production should use *.js
	 * Local development should use *.src.js
	 *
	 * @return string CSS file suffix
	 */
	public static function get_js_suffix() {
		if ( WP_DEBUG ) {
			return '.src.js';
		}
		return '.js';
	}
}

RH_Scripts_And_Styles::get_instance();
