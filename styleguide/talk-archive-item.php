<?php
$basic_args = array(
	'url'        => 'https://example.com',
	'title'      => 'This is a title',
	'excerpt'    => 'This is an excerpt to describe the talk better.',
	'date'       => date( get_option( 'date_format' ) ),
	'event_name' => 'Event Name',
);
$context       = array(
	'basic' => RH_Talks::render_archive_item( $basic_args ),
);
Sprig::out( 'styleguide-talk-archive-item.twig', $context );
