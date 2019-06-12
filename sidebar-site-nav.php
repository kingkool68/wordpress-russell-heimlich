<?php
$escaped = false;
$context = array(
	'site_url'     => get_site_url(),
	'search_query' => get_search_query( $escaped ),
);
Sprig::out( 'sidebar-site-nav.twig', $context );
