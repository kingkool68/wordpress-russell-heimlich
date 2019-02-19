<?php
$stub_comment_data = array(
	array(
		'comment_author' => 'A really long comment author name just to see how this breaks',
	),
	array(
		'comment_ID'     => '4567',
		'comment_author' => 'Joe Schmo',
		'comment_parent' => '1234',
	),
);
$comments          = array();
foreach ( $stub_comment_data as $stub_comment ) {
	$comments[] = RH_Comments::make_stub_comment_object( $stub_comment );
}
$comment_count         = RH_Comments::get_comment_count( count( $comments ) );
$comments_section_args = array(
	'comments_count' => 0,
	'comment_label'  => 'comments',
	'comment_list'   => RH_Comments::get_comments( null, $comments ),
	'comment_form'   => '',
);
$context               = array(
	'comments_section' => RH_Comments::render_comments_section( $comments_section_args ),
);
Sprig::out( 'styleguide-comments.twig', $context );
