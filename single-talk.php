<?php
setup_postdata( get_post() );
$metadata    = RH_Talks::get_data();
$date_values = RH_Helpers::get_date_values( $post->post_date );

$context = array(
	'the_title'    => get_the_title(),
	'the_content'  => apply_filters( 'the_content', get_the_content() ),
	'video_embed'  => RH_Talks::get_video_embed_code(),
	'slides_url'   => $metadata['slides-url'],
	'event_name'   => $metadata['event-name'],
	'event_url'    => $metadata['event-url'],
	'display_date' => $date_values->display_date,
	'machine_date' => $date_values->machine_date,
);
Sprig::out( 'single-talk.twig', $context );
