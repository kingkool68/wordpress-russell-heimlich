<?php
/**
 * Modify how the Sprig templating system works
 */
class RH_Sprig {

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
			$instance->setup_filters();
		}
		return $instance;
	}

	/**
	 * Hook in to WordPress via filters
	 */
	public function setup_filters() {
		add_filter( 'sprig/roots', array( $this, 'filter_sprig_roots' ) );
		add_filter( 'sprig/twig/functions', array( $this, 'filter_sprig_twig_functions' ) );
	}

	/**
	 * Add the styleguide directory to the known directories Sprig should look
	 * for Twig files to render
	 *
	 * @param  array  $paths Places Twig should look for Twig files
	 * @return array         Modified paths
	 */
	public function filter_sprig_roots( $paths = array() ) {
		$paths[] = get_template_directory() . '/styleguide';
		return $paths;
	}

	public function filter_sprig_twig_functions( $functions = array() ) {
		$functions['get_sidebar'] = 'get_sidebar';
		return $functions;
	}
}
RH_Sprig::get_instance();
