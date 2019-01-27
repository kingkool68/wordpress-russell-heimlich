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
		}
		return $instance;
	}

	/**
	 * Helper function for fetching SVG icons
	 *
	 * @param  string $icon  Name of the SVG file in the icons directory
	 * @return string        Inline SVG markup
	 */
	public static function get_icon( $icon = '' ) {
		if ( ! $icon ) {
			return;
		}
		$path = get_template_directory() . '/assets/icons/' . $icon . '.svg';
		$args = [
			'css_class' => 'icon icon-' . $icon,
		];
		return self::get_svg( $path, $args );
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
		return self::get_all_svgs( $directory, $cache_key, $callback );
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
		$defaults  = array(
			'role'      => 'image',
			'css_class' => '',
		);
		$args      = wp_parse_args( $args, $defaults );
		$role_attr = $args['role'];
		$css_class = $args['css_class'];
		if ( is_array( $css_class ) ) {
			$css_class = implode( ' ', $css_class );
		}
		if ( file_exists( $path ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$svg = file_get_contents( $path );
			// Strip the width and height attributes so size can be scaled via CSS font-size
			$svg = preg_replace( '/(width|height)="[\d\.]+"/i', '', $svg );
			$svg = str_replace( '<svg ', '<svg class="' . esc_attr( $css_class ) . '" role="' . esc_attr( $role_attr ) . '" ', $svg );
			return $svg;
		}
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
			! empty( self::$all_svg_cache[ $cache_key ] )
		) {
			return self::$all_svg_cache[ $cache_key ];
		}
		$svgs = array();
		if ( ! $directory || ! file_exists( $directory ) ) {
			return $svgs;
		}
		if ( ! is_callable( $callback ) ) {
			$callback = array( __CLASS__, 'get_svg' );
		}
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
			);
		}
		ksort( $svgs );
		if ( ! empty( $cache_key ) ) {
			self::$all_svg_cache[ $cache_key ] = $svgs;
		}
		return $svgs;
	}
}

RH_SVG::get_instance();
