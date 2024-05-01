<?php
setup_postdata( get_post() );
$data        = RH_Talks::get_data();
$date_values = RH_Helpers::get_date_values( $post->post_date );

$context = array(
	'the_title'        => get_the_title(),
	'the_content'      => apply_filters( 'the_content', get_the_content() ),
	'the_display_date' => $date_values->display_date,
	'the_machine_date' => $date_values->machine_date,
	'the_event_name'   => $data->event_name,
	'the_event_url'    => $data->event_url,
	'the_slides_url'   => $data->slides_url,
	'the_video_url'    => $data->video_url,
	'the_video_embed'  => $data->video_embed,
);
wp_enqueue_style( 'rh-single-talk' );
Sprig::out( 'single-talk.twig', $context );
