<?php
$latest_blog_posts_query = new WP_Query(
	array(
		'posts_per_page' => 5,
		'post_status'    => 'publish',
	)
);
$latest_blog_posts       = RH_Posts::render_archive_items_from_wp_query( $latest_blog_posts_query );
$latest_blog_posts       = '<li>' . implode( '</li><li>', $latest_blog_posts ) . '</li>';
$context                 = array(
	'image_directory_url' => get_template_directory_uri() . '/assets/img',
	'latest_blog_posts'   => $latest_blog_posts,
);
Sprig::out( 'front-page.twig', $context );
