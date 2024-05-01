<?php

$basic_list_item_args = array(
	array(
		'role'      => 'Role',
		'label'     => 'Label',
		'label_url' => 'https://example.com',
		'dates'     => '2023-2024',
	),
	array(
		'role'      => 'A really long line of text that will show what happens when there is wrapping again because sometimes we say too much text',
		'label'     => 'Label',
		'label_url' => 'https://example.com',
		'dates'     => '2022-2023',
	),
	array(
		'role'      => 'Role',
		'label'     => 'Label',
		'label_url' => 'https://example.com',
		'dates'     => '2021-2022',
	),
);

$the_list = array();
foreach ( $basic_list_item_args as $list_item_args ) {
	$the_list[] = RH_Timeline_List_Block::render_list_item( $list_item_args );
}

$basic_args = array(
	'the_title'   => 'The Title',
	'the_list'    => $the_list,
	'the_cta'     => 'The Call to Action',
	'the_cta_url' => 'https://example.com',
);

$args = array(
	'block_name'           => 'rh-timeline-list',
	'the_title'            => 'Timeline List Block',
	'the_description'      => 'A way to display a series of items assocaited with dates.',
	'examples'             => array(
		'basic' => RH_Timeline_List_Block::render( $basic_args ),
	),
	'block_directory_name' => 'timeline-list-block',
);
get_template_part( 'styleguide', 'block', $args );
