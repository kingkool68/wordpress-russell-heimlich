<?php
setup_postdata( get_post() );

$comment_args = array(
	'echo' => false,
);

$comments = get_comments( array(
	'post_id' => get_the_ID(),
	'orderby' => 'comment_date_gmt',
	'order'   => 'ASC',
	'status'  => 'approve',
) );

$context = array(
	'the_title'   => get_the_title(),
	'the_content' => apply_filters( 'the_content', get_the_content() ),
	'comments'    => wp_list_comments( $comment_args, $comments ),
);
Sprig::out( 'single.twig', $context );
