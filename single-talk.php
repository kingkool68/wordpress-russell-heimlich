<?php
setup_postdata( get_post() );
$metadata = RH_Talks::get_data();

$context = array(
	'the_title'   => get_the_title(),
	'the_content' => apply_filters( 'the_content', get_the_content() ),
	'video_embed' => RH_Talks::get_video_embed_code(),
	'slides_url'  => $metadata['slides-url'],
	'event_name'  => $metadata['event-name'],
	'event_url'   => $metadata['event-url'],
);
Sprig::out( 'single-talk.twig', $context );
