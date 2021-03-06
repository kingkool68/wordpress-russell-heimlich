<?php
/**
 * Helper functions that do various things
 */
class RH_Helpers {

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
	 * Simplify generating taxonomy labels by only needing to enter a singular and plural verison
	 *
	 * @param  string $singular  The singular version of the taxonomy label
	 * @param  string $plural    The plural version of the taxonomy label
	 * @param  array  $overrides Specific labels to override that might not fit this pattern
	 * @return array             Taxonomy labels
	 */
	public static function generate_taxonomy_labels( $singular = '', $plural = '', $overrides = array() ) {
		$lc_plural   = strtolower( $plural );
		$uc_plural   = ucwords( $lc_plural );
		$lc_singular = strtolower( $singular );
		$uc_singular = ucwords( $lc_singular );

		$labels = array(
			'name'                       => $uc_plural,
			'singular_name'              => $uc_singular,
			'menu_name'                  => $uc_plural,
			'all_items'                  => 'All ' . $uc_plural,
			'parent_item'                => 'Parent ' . $uc_singular,
			'parent_item_colon'          => 'Parent ' . $uc_singular . ':',
			'new_item_name'              => 'New ' . $uc_singular . ' Name',
			'add_new_item'               => 'Add New ' . $uc_singular,
			'edit_item'                  => 'Edit ' . $uc_singular,
			'update_item'                => 'Update ' . $uc_singular,
			'view_item'                  => 'View ' . $uc_singular,
			'separate_items_with_commas' => 'Separate ' . $lc_plural . ' with commas',
			'add_or_remove_items'        => 'Add or remove ' . $lc_plural,
			'choose_from_most_used'      => 'Choose from the most used',
			'popular_items'              => 'Popular ' . $uc_plural,
			'search_items'               => 'Search ' . $uc_plural,
			'not_found'                  => 'Not Found',
			'no_terms'                   => 'No ' . $lc_plural,
			'items_list'                 => ucfirst( $lc_plural ) . ' list',
			'items_list_navigation'      => ucfirst( $lc_plural ) . ' list navigation',
		);
		return wp_parse_args( $labels, $overrides );
	}

	/**
	 * Simplify generating post type labels by only needing to enter a singular and plural verison
	 *
	 * @param  string $singular  The singular version of the post type label
	 * @param  string $plural    The plural version of the post type label
	 * @param  array  $overrides Specific labels to override that might not fit this pattern
	 * @return array             Post type labels
	 */
	public static function generate_post_type_labels( $singular = '', $plural = '', $overrides = array() ) {
		$lc_plural   = strtolower( $plural );
		$uc_plural   = ucwords( $lc_plural );
		$lc_singular = strtolower( $singular );
		$uc_singular = ucwords( $lc_singular );

		$labels = array(
			'name'                  => $uc_plural,
			'singular_name'         => $uc_singular,
			'menu_name'             => $uc_plural,
			'name_admin_bar'        => $uc_singular,
			'archives'              => $uc_singular . ' Archives',
			'attributes'            => $uc_singular . ' Attributes',
			'parent_item_colon'     => 'Parent ' . $uc_singular . ':',
			'all_items'             => 'All ' . $uc_plural,
			'add_new_item'          => 'Add New ' . $uc_singular,
			'add_new'               => 'Add New',
			'new_item'              => 'New ' . $uc_singular,
			'edit_item'             => 'Edit ' . $uc_singular,
			'update_item'           => 'Update ' . $uc_singular,
			'view_item'             => 'View ' . $uc_singular,
			'view_items'            => 'View ' . $uc_plural,
			'search_items'          => 'Search ' . $uc_singular,
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into ' . $lc_singular,
			'uploaded_to_this_item' => 'Uploaded to this ' . $lc_singular,
			'items_list'            => ucfirst( $lc_plural ) . ' list',
			'items_list_navigation' => ucfirst( $lc_plural ) . ' list navigation',
			'filter_items_list'     => 'Filter ' . $lc_plural . ' list',
		);
		return wp_parse_args( $labels, $overrides );
	}

	/**
	 * Calculate various formats for a given date
	 *
	 * @param  string $date The date to convert to other formats
	 * @return object       The date in other formats
	 */
	public static function get_date_values( $date = '' ) {
		$date = strtotime( $date );
		return (object) array(
			'machine_date'     => date( DATE_W3C, $date ),
			'display_date'     => date( get_option( 'date_format' ), $date ),
			'display_time'     => date( get_option( 'time_format' ), $date ),
			'display_datetime' => date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $date ),
		);
	}
}
RH_Helpers::get_instance();
