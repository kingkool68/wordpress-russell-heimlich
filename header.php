<?php
$context = array(
	'site_url' => get_site_url(),
);
Sprig::out( 'header.twig', $context );
