<?php
/**
 * A block for displaying parts of a resume
 */
class RH_Resume_Section_Block {

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
			'rh-resume-section-block',
			get_template_directory_uri() . '/assets/css/resume-section-block/resume-section-block.min.css',
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
			'name'            => 'rh-resume-section',
			'title'           => 'RH Resume Section',
			'description'     => 'A custom Timeline List block . ',
			'render_callback' => array( $this, 'render_from_block' ),
			'category'        => 'rh',
			'icon'            => 'text',
			'keywords'        => array( 'resume', 'cv' ),
			'enqueue_assets'  => function () {
				wp_enqueue_style( 'rh-resume-section-block' );
			},

		);
		acf_register_block_type( $args );

		$args = array(
			'key'      => 'resume_section_block_fields',
			'title'    => 'Resume Section Block Fields',
			'fields'   => array(
				array(
					'key'   => 'field_resume_section_title',
					'name'  => 'resume_section_title',
					'label' => 'Title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_resume_section_start_date',
					'name'  => 'resume_section_start_date',
					'label' => 'Start Date',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_resume_section_end_date',
					'name'  => 'resume_section_end_date',
					'label' => 'End Date',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_resume_section_company',
					'name'  => 'resume_section_company',
					'label' => 'Company',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_resume_section_company_url',
					'name'  => 'resume_section_company_url',
					'label' => 'Company URL',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_resume_section_location',
					'name'  => 'resume_section_location',
					'label' => 'Location',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_resume_section_skills',
					'name'         => 'resume_section_skills',
					'label'        => 'Skills',
					'type'         => 'textarea',
					'instructions' => 'One skill per line',
				),
				array(
					'key'          => 'field_resume_section_description',
					'name'         => 'resume_section_description',
					'label'        => 'Description',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => false,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => ' == ',
						'value'    => 'acf/rh-resume-section',
					),
				),
			),
		);
			acf_add_local_field_group( $args );
	}

	/**
	 * Render component
	 *
	 * @param array $args Arguments to modify what is rendered
	 */
	public static function render( $args = array() ) {
		$defaults = array(
			'attributes'             => array(),
			'additional_css_classes' => '',
			'the_title'              => '',
			'the_start_date'         => '',
			'the_machine_start_date' => '',
			'the_end_date'           => '',
			'the_machine_end_date'   => '',
			'the_duration'           => '',
			'the_company'            => '',
			'the_company_url'        => '',
			'the_location'           => '',
			'the_skills'             => array(),
			'the_description'        => '',
		);
		$context  = RH_Blocks::do_context( $args, $defaults );

		$context['the_title']       = apply_filters( 'the_title', $context['the_title'] );
		$context['the_description'] = apply_filters( 'the_content', $context['the_description'] );
		if ( is_string( $context['the_skills'] ) ) {
			$context['the_skills'] = explode( PHP_EOL, $context['the_skills'] );
			$context['the_skills'] = array_filter( $context['the_skills'] );
		}

		$start_date_values = array();
		if ( ! empty( $context['the_start_date'] ) ) {
			$start_date_values = static::get_date_values( $context['the_start_date'] );
		}

		$end_date_values = array();
		if ( ! empty( $context['the_end_date'] ) ) {
			$end_date_values = static::get_date_values( $context['the_end_date'] );
		}

		if ( ! empty( $end_date_values->seconds ) && ! empty( $start_date_values->seconds ) ) {
			$context['the_duration'] = static::human_time_diff( 2, $start_date_values->seconds, $end_date_values->seconds );
			// $diff = str_replace( array( 'year', 'month' ), array( 'yr', 'mo' ), $diff );
			$context['the_start_date']         = $start_date_values->display_date;
			$context['the_machine_start_date'] = $start_date_values->machine_date;
			$context['the_end_date']           = $end_date_values->display_date;
			$context['the_machine_end_date']   = $end_date_values->machine_date;
		}

		wp_enqueue_style( 'rh-resume-section-block' );
		return Sprig::render( 'resume-section-block.twig', $context );
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

		$args = array(
			'attributes'             => $additional->attributes,
			'additional_css_classes' => $additional->css_class,
			'the_title'              => get_field( 'resume_section_title' ),
			'the_start_date'         => get_field( 'resume_section_start_date' ),
			'the_end_date'           => get_field( 'resume_section_end_date' ),
			'the_company'            => get_field( 'resume_section_company' ),
			'the_company_url'        => get_field( 'resume_section_company_url' ),
			'the_location'           => get_field( 'resume_section_location' ),
			'the_skills'             => get_field( 'resume_section_skills' ),
			'the_description'        => get_field( 'resume_section_description' ),
		);
		echo static::render( $args );
	}

	public static function get_date_values( $the_date = '' ) {
		$the_date_to_use = $the_date;
		if ( strtolower( $the_date ) === 'present' ) {
			$the_date_to_use = gmdate( 'Y-M-d', time() );
		}
		$timezone_string = get_option( 'timezone_string' );
		if ( empty( $timezone_string ) ) {
			$timezone_string = 'Etc/GMT';
		}
		$timezone = new DateTimeZone( $timezone_string );
		$date     = new DateTime( $the_date_to_use, $timezone );
		$output   = array(
			'machine_date' => $date->format( DATE_W3C ),
			'display_date' => $date->format( 'M Y' ),
			'seconds'      => $date->format( 'U' ),
		);
		if ( strtolower( $the_date ) === 'present' ) {
			$output['display_date'] = $the_date;
		}
		return (object) $output;
	}

	/**
	 * My own human time diff function from http://www.php.net/manual/en/ref.datetime.php#90989
	 *
	 * @param  integer       $levels Precision of time diff
	 * @param  integer       $from   Time to start at in seconds
	 * @param  integer|false $to     Time to end at in seconds
	 * @return string                Human-friendly time diff
	 */
	public static function human_time_diff( $levels = 2, $from, $to = false ) {
		if ( ! $to ) {
			$to = current_time( 'U' );
		}
		$blocks = array(
			array(
				'name'   => 'year',
				'amount' => 60 * 60 * 24 * 365,
			),
			array(
				'name'   => 'month',
				'amount' => 60 * 60 * 24 * 31,
			),
			array(
				'name'   => 'week',
				'amount' => 60 * 60 * 24 * 7,
			),
			array(
				'name'   => 'day',
				'amount' => 60 * 60 * 24,
			),
			array(
				'name'   => 'hour',
				'amount' => 60 * 60,
			),
			array(
				'name'   => 'minute',
				'amount' => 60,
			),
			array(
				'name'   => 'second',
				'amount' => 1,
			),
		);

		$diff = abs( $from - $to );

		$current_level = 1;
		$result        = array();
		foreach ( $blocks as $block ) {
			if ( $current_level > $levels ) {
				break;
			}
			if ( $diff / $block['amount'] >= 1 ) {
				$amount = floor( $diff / $block['amount'] );
				$plural = '';
				if ( $amount > 1 ) {
					$plural = 's';
				}
				$result[] = $amount . ' ' . $block['name'] . $plural;
				$diff    -= $amount * $block['amount'];
				++$current_level;
			}
		}

		return implode( ' ', $result );
	}
}
RH_Resume_Section_Block::get_instance();
