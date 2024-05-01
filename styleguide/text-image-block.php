<?php

$default_image = RH_Media::render_image(
	array(
		'image_src' => 'https://dummyimage.com/495x795.png',
	)
);

$basic_args = array(
	'image'           => $default_image,
	'image_alignment' => 'left',
	'kicker'          => 'Kicker',
	'headline'        => 'Headline',
	'headline_url'    => 'https://example.com',
	'description'     => 'This is some description text that we can use below the headline.',
);

$right_aligned_args = wp_parse_args(
	array(
		'image_alignment' => 'right',
	),
	$basic_args
);


$args = array(
	'block_name'           => 'rh-text-image',
	'the_title'            => 'Text/Image Block',
	'the_description'      => 'For displaying text side-by-side with an image.',
	'examples'             => array(
		'basic'         => RH_Text_Image_Block::render( $basic_args ),
		'right-aligned' => RH_Text_Image_Block::render( $right_aligned_args ),
	),
	'block_directory_name' => 'text-image-block',
);
get_template_part( 'styleguide', 'block', $args );
