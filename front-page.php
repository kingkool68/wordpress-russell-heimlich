<?php
$context = array(
	'image_directory_url' => get_template_directory_uri() . '/assets/img',
);
Sprig::out( 'front-page.twig', $context );
