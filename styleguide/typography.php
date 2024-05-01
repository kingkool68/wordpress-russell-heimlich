<?php
$specimans = array(
	array(
		'label'  => 'h1',
		'tag'    => 'h1',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'h1 italic',
		'tag'    => 'h1',
		'weight' => '',
		'italic' => true,
	),
	array(
		'label'  => 'h2',
		'tag'    => 'h2',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'h2 italic',
		'tag'    => 'h2',
		'weight' => '',
		'italic' => true,
	),
	array(
		'label'  => 'h3',
		'tag'    => 'h3',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'h3 italic',
		'tag'    => 'h3',
		'weight' => '',
		'italic' => true,
	),
	array(
		'label'  => 'h4',
		'tag'    => 'h4',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'h4 italic',
		'tag'    => 'h4',
		'weight' => '',
		'italic' => true,
	),
	array(
		'label'  => 'h5',
		'tag'    => 'h5',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'h5 italic',
		'tag'    => 'h5',
		'weight' => '',
		'italic' => true,
	),
	array(
		'label'  => 'h6',
		'tag'    => 'h6',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'h6 italic',
		'tag'    => 'h6',
		'weight' => '',
		'italic' => true,
	),
	array(
		'label'  => 'body',
		'tag'    => 'p',
		'weight' => '',
		'italic' => false,
	),
	array(
		'label'  => 'body bold',
		'tag'    => 'p',
		'weight' => '700',
		'italic' => false,
	),
	array(
		'label'  => 'body italic',
		'tag'    => 'p',
		'weight' => '',
		'italic' => true,
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
