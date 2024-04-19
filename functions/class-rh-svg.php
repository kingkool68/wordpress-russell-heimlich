<?php
/**
 * Helpers for rendering SVGs inline
 */
class RH_SVG {

	/**
	 * Cache of data after querying for all SVG files
	 * on the filesystem so it is only performed once per request max
	 *
	 * @var array
	 */
	private static $all_svg_cache = array();

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
	 * Hook into WordPress via filters
	 */
	public function setup_filters() {
		add_filter( 'sprig/twig/filters', array( $this, 'filter_sprig_twig_filters' ) );
	}

	/**
	 * Create a twig filter to randomize SVG IDs
	 *
	 * @param  array $filters Group of Twig filters to modify
	 */
	public function filter_sprig_twig_filters( $filters = array() ) {
		$filters['randomize_svg_id'] = array( $this, 'randomize_svg_id' );
		return $filters;
	}

	/**
	 * Add random string to beginning of IDs in an SVG to make them unqiue.
	 *
	 * If two or more of the same SVGs are on the same page and they use IDs, those IDs need to be unique or the SVG fails to render
	 *
	 * @param  string $svg The SVG markup to modify
	 */
	public function randomize_svg_id( $svg = '' ) {
		$length     = 5;
		$random_str = substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyz' ), 1, $length ) . '-';
		$svg        = str_replace( 'url(#', 'url(#' . $random_str, $svg );
		$svg        = str_replace( 'id="', 'id="' . $random_str, $svg );
		return $svg;
	}

	/**
	 * Helper function for fetching SVG icons
	 *
	 * @param  string $icon  Name of the SVG file in the icons directory
	 * @param array  $args Arguments to modify the defaults passed to static::get_svg()
	 *
	 * @return string        Inline SVG markup
	 */
	public static function get_icon( $icon = '', $args = array() ) {
		if ( ! $icon ) {
			return;
		}
		$path     = get_template_directory() . '/assets/icons/' . $icon . '.svg';
		$defaults = array(
			'css_class' => 'icon icon-' . $icon,
		);
		$args     = wp_parse_args( $args, $defaults );
		return static::get_svg( $path, $args );
	}

	/**
	 * Helper function to get an icon by slug if $icon is empty
	 *
	 * @param  string $icon      The icon to check if empty
	 * @param  string $icon_slug The slug of the icon to get if $icon is empty
	 * @param  array  $args      Arguments to pass to get_icon()
	 */
	public static function maybe_get_icon( $icon = '', $icon_slug = '', $args = array() ) {
		$callback = array( __CLASS__, 'get_icon' );
		return static::maybe_get_svg( $icon, $icon_slug, $args, $callback );
	}

	/**
	 * Read all of the SVG files in the /assets/icons/ directory
	 *
	 * @return array Objects contaning the label and contents of all Icons SVGs
	 */
	public static function get_all_icons() {
		$directory = get_template_directory() . '/assets/icons/';
		$cache_key = 'icons';
		$callback  = array( __CLASS__, 'get_icon' );
		return static::get_all_svgs( $directory, $cache_key, $callback );
	}

	/**
	 * Read all of the SVG files in the /assets/icons/ directory formatted for an Advanced Custom Fields select field
	 *
	 * @return array Array containing the SVG contents and label
	 */
	public static function get_all_icons_for_acf() {
		$directory = get_template_directory() . '/assets/icons/';
		$cache_key = 'icons';
		$callback  = array( __CLASS__, 'get_icon' );
		return static::get_all_svgs_for_acf( $directory, $cache_key, $callback );
	}

	/**
	 * Helper function for fetching SVG logos
	 *
	 * @param  string $logo Name of the SVG file in the logos directory
	 * @param array  $args  Arguments to modify the defaults passed to static::get_svg()
	 *
	 * @return string        Inline SVG markup
	 */
	public static function get_logo( $logo = '', $args = array() ) {
		if ( ! $logo ) {
			return;
		}
		$path     = get_template_directory() . '/assets/logos/' . $logo . '.svg';
		$defaults = array(
			'css_class' => 'logo logo-' . $logo,
		);
		$args     = wp_parse_args( $args, $defaults );
		return static::get_svg( $path, $args );
	}

	/**
	 * Helper function to get a logo by slug if $logo is empty
	 *
	 * @param  string $logo      The logo to check if empty
	 * @param  string $logo_slug The slug of the logo to get if $logo is empty
	 * @param  array  $args      Arguments to pass to static::get_logo()
	 */
	public static function maybe_get_logo( $logo = '', $logo_slug = '', $args = array() ) {
		$callback = array( __CLASS__, 'get_logo' );
		return static::maybe_get_svg( $logo, $logo_slug, $args, $callback );
	}

	/**
	 * Read all of the SVG files in the /assets/logos/ directory
	 *
	 * @return array Objects contaning the label and contents of all logo SVGs
	 */
	public static function get_all_logos() {
		$directory = get_template_directory() . '/assets/logos/';
		$cache_key = 'logos';
		$callback  = array( __CLASS__, 'get_logo' );
		return static::get_all_svgs( $directory, $cache_key, $callback );
	}

	/**
	 * Read all of the SVG files in the /assets/logos/ directory formatted for an Advanced Custom Fields select field
	 *
	 * @return array Array containing the SVG contents and label
	 */
	public static function get_all_logos_for_acf() {
		$directory = get_template_directory() . '/assets/logos/';
		$cache_key = 'logos';
		$callback  = array( __CLASS__, 'get_logo' );
		return static::get_all_svgs_for_acf( $directory, $cache_key, $callback );
	}

	/**
	 * Generic helper to modify the markup for a given path to an SVG
	 *
	 * @param  string $path  Absolute path to the SVG file
	 * @param  array  $args  Args to modify attributes of the SVG
	 * @return string        Inline SVG markup
	 */
	public static function get_svg( $path = '', $args = array() ) {
		if ( ! $path ) {
			return;
		}
		$defaults = array(
			'role'          => 'img',
			'css_class'     => '',
			'add_css_class' => '',
		);
		$args     = wp_parse_args( $args, $defaults );

		$args['css_class'] = RH_Helpers::css_class( $args['css_class'], $args['add_css_class'] );
		if ( file_exists( $path ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$svg = file_get_contents( $path );
			// Strip the width and height attributes so size can be scaled via CSS font-size
			// $svg = preg_replace( '/\s(width|height)="[\d\.]+"/i', '', $svg );
			$svg = str_replace( '<svg ', '<svg class="' . esc_attr( $args['css_class'] ) . '" role="' . esc_attr( $args['role'] ) . '" ', $svg );
			return $svg;
		}
	}

	/**
	 * Maybe get an SVG by the $svg_slug if the provided $svg is empty
	 *
	 * @param  string $svg      The SVG to check if is empty or not
	 * @param  string $svg_slug The slug of the SVG to get if $svg is empty
	 * @param  array  $args     Optional arguments to pass to the callback
	 * @param  string $callback The callback to use for getting the SVG using the provided slug
	 */
	public static function maybe_get_svg( $svg = '', $svg_slug = '', $args = array(), $callback = '' ) {
		if ( ! is_callable( $callback ) ) {
			$callback = array( __CLASS__, 'get_svg' );
		}

		if ( ! empty( $svg ) ) {
			return $svg;
		}
		if ( empty( $svg_slug ) ) {
			return;
		}
		return $callback( $svg_slug, $args );
	}

	/**
	 * Get all of the SVG files for a given directory
	 *
	 * @param  string $directory Directory to search for SVGs in
	 * @param  string $cache_key Key to use to read/set the cache
	 * @param  string $callback  Callback used to fetch the SVG contents
	 * @return array             Objects containing the SVG contents and label
	 */
	public static function get_all_svgs( $directory = '', $cache_key = '', $callback = '' ) {
		if (
			! empty( $cache_key ) &&
			! empty( static::$all_svg_cache[ $cache_key ] )
		) {
			return static::$all_svg_cache[ $cache_key ];
		}
		$svgs = array();
		if ( ! $directory || ! file_exists( $directory ) ) {
			return $svgs;
		}
		if ( ! is_callable( $callback ) ) {
			$callback = array( __CLASS__, 'get_svg' );
		}

		$url_search    = get_template_directory();
		$url_replace   = get_template_directory_uri();
		$url_directory = str_replace( $url_search, $url_replace, $directory );

		$iterator = new DirectoryIterator( $directory );
		foreach ( $iterator as $file ) {
			if ( ! $file->isFile() ) {
				continue;
			}
			$parts = explode( '.', $file->getFilename() );
			if ( empty( $parts[1] ) || 'svg' !== strtolower( $parts[1] ) ) {
				continue;
			}
			$filename          = $parts[0];
			$svg               = $callback( $filename );
			$svgs[ $filename ] = (object) array(
				'svg'   => $svg,
				'label' => $filename,
				'url'   => $url_directory . $file->getFilename(),
			);
		}
		ksort( $svgs );
		if ( ! empty( $cache_key ) ) {
			static::$all_svg_cache[ $cache_key ] = $svgs;
		}
		return $svgs;
	}

	/**
	 * Get all of the SVG files for a given directory formatted for use in an Advanced Custom Fields select field
	 *
	 * @param  string $directory Directory to search for SVGs in
	 * @param  string $cache_key Key to use to read/set the cache
	 * @param  string $callback  Callback used to fetch the SVG contents
	 * @return array             Array containing the SVG contents and label
	 */
	public static function get_all_svgs_for_acf( $directory = '', $cache_key = '', $callback = '' ) {
		$all_svgs = static::get_all_svgs( $directory, $cache_key, $callback );
		$svgs     = array();
		foreach ( $all_svgs as $key => $svg ) {
			$svgs[ $key ] = $svg->svg . ' ' . $key;
		}
		return $svgs;
	}
}

RH_SVG::get_instance();
