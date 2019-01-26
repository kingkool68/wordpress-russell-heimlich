<?php
setup_postdata( get_post() );

$context = array(
	'the_title'   => get_the_title(),
	'the_content' => apply_filters( 'the_content', get_the_content() ),
	'comments'    => RH_Comments::get_comments(),
);
Sprig::out( 'single.twig', $context );
