<?php
/**
 * General Block Settings
 */
class RH_Blocks {

	/**
	 * Store location parameters for blocks that want to support global fields
	 *
	 * @var array Collection of location parameters
	 */
	public static $global_field_locations = array();


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
	 * Hook into WordPress via actions
	 */
	public function setup_actions() {
		add_action( 'init', array( $this, 'action_init' ), 101 ); // After \Syntax_Highlighting_Code_Block\init() runs
	}

	/**
	 * Hook in to WordPress via filters
	 */
	public function setup_filters() {
		add_filter( 'block_categories_all', array( $this, 'filter_block_categories_all' ), 10, 1 );

		// Disable all frontend styles of the Syntax Highlighting Code block
		add_filter( 'syntax_highlighting_code_block_styling', '__return_false' );
		add_filter( 'sprig/roots', array( $this, 'filter_sprig_roots' ) );
		add_filter( 'acf/prepare_field_group_for_import', array( $this, 'filter_acf_prepare_field_group_for_import' ) );
	}

	/**
	 * Set the default behavior for code blocks
	 */
	public function action_init() {
		$block_type                                     = WP_Block_Type_Registry::get_instance()->get_registered( 'core/code' );
		$block_type->attributes['wrapLines']['default'] = true;
	}

	/**
	 * Add RH_ as a block category
	 *
	 * @param array $categories The categories to modify
	 */
	public function filter_block_categories_all( $categories = array() ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'rh',
					'title' => 'RH',
					'icon'  => 'wordpress',
				),
			)
		);
	}

	/**
	 * Add every directory in the /blocks/ directory to the possible path for a Twig file
	 *
	 * @param  array $paths Places Twig should look for Twig files
	 * @return array        Modified paths
	 */
	public function filter_sprig_roots( $paths = array() ) {
		$iter = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				get_template_directory() . '/blocks/',
				RecursiveDirectoryIterator::SKIP_DOTS
			),
			RecursiveIteratorIterator::SELF_FIRST,
			RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
		);
		foreach ( $iter as $path => $dir ) {
			if ( $dir->isDir() ) {
				$paths[] = $path;
			}
		}
		return $paths;
	}

	/**
	 * Process ACF field groups looking for field groups that have the 'global_fields' key set
	 * These field groups will need the global fields added to them which happens dynamically
	 *
	 * @param  array $field_group The field_group args being imported into PHP by ACF
	 */
	public function filter_acf_prepare_field_group_for_import( $field_group = array() ) {
		if ( ! empty( $field_group['global_fields'] ) ) {
			$global_fields = $field_group['global_fields'];
			if ( ! is_array( $global_fields ) ) {
				$global_fields = array( $global_fields );
			}
			foreach ( $global_fields as $variation_key ) {
				if ( ! empty( $field_group['location'][0] ) ) {
					static::$global_field_locations[ $variation_key ][] = $field_group['location'][0];
				}
			}
		}
		$key = str_replace( '_fields', '', $field_group['key'] );
		if ( ! empty( static::$global_field_locations[ $key ] ) ) {
			$field_group['location'] = static::$global_field_locations[ $key ];
		}
		return $field_group;
	}


	/**
	 * Get a list of fields associated with a given ACF block
	 *
	 * @param  string $block_name The name of the block to get the ACF fields for
	 * @return array              List of field data for the given block or empty if not found
	 */
	public static function get_acf_fields_for_block( $block_name = '' ) {
		// Make sure the block name starts with "acf/"
		if ( ! str_starts_with( $block_name, 'acf/' ) ) {
			$block_name = 'acf/' . $block_name;
		}
		$acf_group_data = acf_get_local_store( 'groups' )->get_data();
		foreach ( $acf_group_data as $data ) {
			$locations = $data['location'];
			foreach ( $locations as $location ) {
				if ( empty( $location ) || ! is_array( $location ) ) {
					continue;
				}
				foreach ( $location as $group ) {
					if ( empty( $group['param'] ) || empty( $group['value'] ) ) {
						continue;
					}
					if ( $group['param'] === 'block' && $group['value'] === $block_name ) {
						$key = $data['key'];
						return acf_get_fields( $key );
					}
				}
			}
		}
		return array();
	}

	/**
	 * Merge default values with argument values and process all values before sending them to a Twig template
	 *
	 * @param  array $args     Argument values to change what is rendered
	 * @param  array $defaults Default values for what should be rendered
	 */
	public static function do_context( $args = array(), $defaults = array() ) {
		$c = wp_parse_args( $args, $defaults );
		if ( ! empty( $c['attributes'] ) ) {
			if ( is_string( $c['attributes'] ) ) {
				$c['attributes'] = array( $c['attributes'] );
			}
			$c['attributes'] = CoderPad_Helpers::build_html_attributes( $c['attributes'] );
		} else {
			$c['attributes'] = '';
		}
		if ( ! empty( $c['additional_css_classes'] ) ) {
			$c['additional_css_classes'] = CoderPad_Helpers::css_class( '', $c['additional_css_classes'] );
		}
		return $c;
	}

	/**
	 * Get attributes when an ACF block is rendered. Handles processing global field values in one place.
	 *
	 * @param  array $block The ACF block settings being rendered by a template
	 */
	public static function get_attributes_from_block( $block = array() ) {
		$output = array(
			'css_class'  => '',
			'attributes' => '',
		);
		if ( ! empty( $block['className'] ) ) {
			$output['css_class'] = $block['className'];
		}

		$attrs       = array();
		$linline_css = array();

		if ( ! empty( $block['anchor'] ) ) {
			$attrs['id'] = sanitize_title( $block['anchor'] );
		}

		if ( ! empty( $block['name'] ) ) {
			$attrs['data-block-name'] = str_replace( 'acf/', '', $block['name'] );
		}

		if ( ! empty( $linline_css ) ) {
			$attrs['style'] = '';
			foreach ( $linline_css as $property => $value ) {
				$property        = esc_html( $property );
				$value           = esc_html( $value );
				$attrs['style'] .= "{$property}:{$value};";
			}
		}
		$output['attributes'] = $attrs;
		return (object) $output;
	}
}
RH_Blocks::get_instance();
