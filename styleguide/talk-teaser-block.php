<?php

$default_image = RH_Media::render_image(
	array(
		'image_src' => 'https://dummyimage.com/640x480.png',
	)
);

$basic_args = array(
	'the_url'        => '#',
	'the_title'      => 'The Talk Title',
	'the_image'      => $default_image,
	'the_event_name' => 'The Event Name',
	'the_date'       => 'November 20, 2016',
	'the_excerpt'    => 'This is teaser text that describes what the talk is about hooking the reader to click through.',
);

$args = array(
	'block_name'           => 'rh-talk-teaser',
	'the_title'            => 'Talk teaser Block',
	'the_description'      => 'Tease a talk to entice visitors to check out the talk.',
	'examples'             => array(
		'basic' => RH_Talk_Teaser_Block::render( $basic_args ),
	),
	'block_directory_name' => 'talk-teaser-block',
);
get_template_part( 'styleguide', 'block', $args );
