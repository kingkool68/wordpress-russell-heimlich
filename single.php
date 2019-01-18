<?php
setup_postdata( get_post() );

$context = array(
	'the_title'   => get_the_title(),
	'the_content' => apply_filters( 'the_content', get_the_content() ),
);
Sprig::out( 'single.twig', $context );
