<?php
$latest_blog_posts_query = new WP_Query(
	array(
		'posts_per_page' => 5,
		'post_status'    => 'publish',
	)
);
$latest_blog_posts       = array();
foreach ( $latest_blog_posts_query->posts as $post ) {
	$latest_blog_posts[] = array(
		'title' => apply_filters( 'the_title', $post->post_title ),
		'url'   => get_permalink( $post->ID ),
	);
}
$context = array(
	'image_directory_url' => get_template_directory_uri() . '/assets/img',
	'latest_blog_posts' => $latest_blog_posts,
);
Sprig::out( 'front-page.twig', $context );
