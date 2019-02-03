<?php
$context = array(
	'upcoming_talks' => RH_Talks::get_upcoming_talks(),
	'talks'          => RH_Talks::render_archive_items_from_wp_query(),
);
Sprig::out( 'archive-talk.twig', $context );
