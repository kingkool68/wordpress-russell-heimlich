<?php
/**
 * A block for displaying a list of timeline items
 */
class RH_Timeline_List_Block {

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
			'rh-timeline-list-block',
			get_template_directory_uri() . '/assets/css/timeline-list-block/timeline-list-block.min.css',
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
			'name'            => 'rh-timeline-list',
			'title'           => 'RH Timeline List',
			'description'     => 'A custom Timeline List block . ',
			'render_callback' => array( $this, 'render_from_block' ),
			'category'        => 'rh',
			'icon'            => 'list-view',
			'keywords'        => array( 'timeline', 'list', 'dates' ),
			'enqueue_assets'  => function () {
				wp_enqueue_style( 'rh-timeline-list-block' );
			},

		);
		acf_register_block_type( $args );

		$args = array(
			'key'      => 'timeline_list_block_fields',
			'title'    => 'Timeline List Block Fields',
			'fields'   => array(
				array(
					'key'   => 'field_timeline_list_title',
					'name'  => 'timeline_list_title',
					'label' => 'Title',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_timeline_list_items',
					'name'         => 'timeline_list_items',
					'label'        => 'Items',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Add Item',
					'sub_fields'   => array(
						array(
							'key'   => 'field_timeline_list_item_label',
							'name'  => 'timeline_list_item_label',
							'label' => 'Label',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_timeline_list_item_label_url',
							'name'  => 'timeline_list_item_label_url',
							'label' => 'Label URL',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_timeline_list_item_dates',
							'name'  => 'timeline_list_item_dates',
							'label' => 'Dates',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'   => 'field_timeline_list_cta',
					'name'  => 'timeline_list_cta',
					'label' => 'Call to Action',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_timeline_list_cta_url',
					'name'  => 'timeline_list_cta_url',
					'label' => 'Call to Action URL',
					'type'  => 'text',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => ' == ',
						'value'    => 'acf/rh-timeline-list',
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
			'the_title'              => '',
			'the_list'               => array(),
			'the_cta'                => '',
			'the_cta_url'            => '',
			'the_cta_icon'           => '',
			'the_cta_icon_slug'      => 'arrow-long-right',
		);
		$context  = RH_Blocks::do_context( $args, $defaults );

		$context['the_title']    = apply_filters( 'the_title', $context['the_title'] );
		$context['the_cta_icon'] = RH_SVG::maybe_get_icon( $context['the_cta_icon'], $context['the_cta_icon_slug'] );

		wp_enqueue_style( 'rh-timeline-list-block' );
		return Sprig::render( 'timeline-list-block.twig', $context );
	}

	/**
	 * Render from block callback function
	 *
	 * @param   array        $block The block settings and attributes.
	 * @param   string       $content The block inner HTML (empty).
	 * @param   bool         $is_preview True during AJAX preview.
	 * @param   (int|string) $post_id The post ID this block is saved to.
	 */
	public function render_from_block( $block = array(), $content = '', $is_preview = false, $post_id = 0 ) {
		$additional = RH_Blocks::get_attributes_from_block( $block );

		$the_list = array();
		while ( have_rows( 'timeline_list_items' ) ) :
			the_row();
			$the_list[] = array(
				'label'     => get_sub_field( 'timeline_list_item_label' ),
				'label_url' => get_sub_field( 'timeline_list_item_label_url' ),
				'dates'     => get_sub_field( 'timeline_list_item_dates' ),
			);
		endwhile;

		$args = array(
			'attributes'             => $additional->attributes,
			'additional_css_classes' => $additional->css_class,
			'the_title'              => get_field( 'timeline_list_title' ),
			'the_list'               => $the_list,
			'the_cta'                => get_field( 'timeline_list_cta' ),
			'the_cta_url'            => get_field( 'timeline_list_cta_url' ),
		);
		echo static::render( $args );
	}
}
RH_Timeline_List_Block::get_instance();
