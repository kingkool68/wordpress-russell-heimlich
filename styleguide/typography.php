<?php
$specimans = array(
	array(
		'label'  => 'h1',
		'tag'    => 'h1',
		'weight' => '',
	),
	array(
		'label'  => 'h1 bold',
		'tag'    => 'h1',
		'weight' => '700',
	),
	array(
		'label'  => 'h2',
		'tag'    => 'h2',
		'weight' => '',
	),
	array(
		'label'  => 'h2 bold',
		'tag'    => 'h2',
		'weight' => '700',
	),
	array(
		'label'  => 'h3',
		'tag'    => 'h3',
		'weight' => '',
	),
	array(
		'label'  => 'h3 bold',
		'tag'    => 'h3',
		'weight' => '700',
	),
	array(
		'label'  => 'h4',
		'tag'    => 'h4',
		'weight' => '',
	),
	array(
		'label'  => 'h4 bold',
		'tag'    => 'h4',
		'weight' => '700',
	),
	array(
		'label'  => 'h5',
		'tag'    => 'h5',
		'weight' => '',
	),
	array(
		'label'  => 'h5 bold',
		'tag'    => 'h5',
		'weight' => '700',
	),
	array(
		'label'  => 'h6',
		'tag'    => 'h6',
		'weight' => '',
	),
	array(
		'label'  => 'h6 bold',
		'tag'    => 'h6',
		'weight' => '700',
	),
	array(
		'label'  => 'body',
		'tag'    => 'p',
		'weight' => '',
	),
);

$sample_text = 'A wizard’s job is to vex chumps quickly in fog';
if ( ! empty( $_GET['sample-text'] ) ) {
	$sample_text = sanitize_text_field( wp_unslash( $_GET['sample-text'] ) );
}
$context = array(
	'specimans'   => $specimans,
	'sample_text' => $sample_text,
);
Sprig::out( 'styleguide-typography.twig', $context );
