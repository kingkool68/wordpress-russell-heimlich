<?php
$context = array(
	'the_content'   => apply_filters( 'the_content', get_the_content() ),
);
Sprig::out( 'front-page.twig', $context );
