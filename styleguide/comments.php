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
	array(
		'comment_author' => 'Random Guy',
	),
	array(
		'comment_author'       => 'Russell Heimlich',
		'comment_author_email' => 'info@russellheimlich.com',
		'comment_content'      => 'This is a comment body message. Let us see how this looks.' . "\n\n" . 'And here is another paragraph.',
		'comment_parent'       => '4567',
		'comment_ID'           => '45671',
	),
);
$my_comments       = array();
foreach ( $stub_comment_data as $stub_comment ) {
	$my_comments[] = RH_Comments::make_stub_comment_object( $stub_comment );
}
$comment_count         = RH_Comments::get_comment_count( count( $my_comments ) );
$comments_section_args = array(
	'comments_count' => $comment_count->number,
	'comment_label'  => $comment_count->label,
	'comments_list'  => RH_Comments::get_comments( null, $my_comments ),
	'comment_form'   => RH_Comments::get_comment_form(),
);

$context = array(
	'comments_section' => RH_Comments::render_comments_section( $comments_section_args ),
);
Sprig::out( 'styleguide-comments.twig', $context );
