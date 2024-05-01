<?php
$the_loop = array();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		$the_loop[] = Sprig::do_action( 'rh/the_loop_' . get_post_type(), $post, $wp_query->current_post );
	endwhile;
endif;

$context = array(
	'the_loop'   => implode( "\n", $the_loop ),
	'pagination' => RH_Pagination::render_from_wp_query(),
);
Sprig::out( 'index.twig', $context );
