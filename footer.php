<?php
$social_media_urls = array(
	'https://twitter.com/kingkool68',
	'https://www.facebook.com/russellh',
	'https://www.linkedin.com/in/kingkool68/',
	'https://github.com/kingkool68',
	'https://www.instagram.com/kingkool68/',
	'https://www.youtube.com/user/kingkool68',
	'https://www.last.fm/user/kingkool68',
	'https://profiles.wordpress.org/kingkool68',
	'https://www.flickr.com/people/kingkool68/',
	'http://stackoverflow.com/users/1119655',
);

$context           = array(
	'site_url'          => get_site_url(),
	'social_media_urls' => $social_media_urls,
	'year'              => date( 'Y' ),
);
Sprig::out( 'footer.twig', $context );
