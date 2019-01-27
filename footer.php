<?php
function get_footer_social_media_item( $url = '', $icon_name = '', $title_attr = '' ) {
	$context = array(
		'url'        => $url,
		'icon_name'  => $icon_name,
		'icon'       => RH_SVG::get_icon( $icon_name ),
		'title_attr' => $title_attr,
	);
	return Sprig::render( 'footer-social-media-item.twig', $context );
}
$social_media_items = array(
	get_footer_social_media_item(
		'https://twitter.com/kingkool68',
		'twitter',
		'Follow @kingkool68 on Twitter'
	),
	get_footer_social_media_item(
		'https://www.facebook.com/russellh',
		'facebook',
		'Friend Russell on Facebook'
	),
	get_footer_social_media_item(
		'https://www.linkedin.com/in/kingkool68/',
		'linkedin',
		'Connect on LinkedIn'
	),
	get_footer_social_media_item(
		'https://github.com/kingkool68',
		'github',
		'Browse my code on GitHub'
	),
	get_footer_social_media_item(
		'https://www.instagram.com/kingkool68/',
		'instagram',
		'Follow @kingkool68 on Instagram'
	),
	get_footer_social_media_item(
		'https://www.youtube.com/user/kingkool68',
		'youtube',
		'kingkool68 on YouTube'
	),
	get_footer_social_media_item(
		'https://www.last.fm/user/kingkool68',
		'lastfm',
		'Last.FM profile'
	),
	get_footer_social_media_item(
		'https://profiles.wordpress.org/kingkool68',
		'wordpress',
		'WordPress.org Profile'
	),
	get_footer_social_media_item(
		'http://stackoverflow.com/users/1119655',
		'stackoverflow',
		'Stack Overflow profile'
	),
);

$context = array(
	'site_url'           => get_site_url(),
	'social_media_items' => implode( ' ', $social_media_items ),
	'year'               => date( 'Y' ),
);
Sprig::out( 'footer.twig', $context );
