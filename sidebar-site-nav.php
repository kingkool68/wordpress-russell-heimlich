<?php
$context = array(
	'site_url' => get_site_url(),
);
Sprig::out( 'sidebar-site-nav.twig', $context );
