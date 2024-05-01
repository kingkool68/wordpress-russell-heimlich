<?php
$context = array(
	'image_path' => get_template_directory_uri() . '/assets/img',
);
Sprig::out( 'styleguide-cards.twig', $context );
