<?php
/**
 * Various functions
 *
 * @package Russell Heimlich
 */

$files_to_require = array(
	'debugging.php',
	'class-rh-helpers.php',
	'class-rh-sprig.php',
	'class-rh-svg.php',

	'class-rh-pagination.php',
	'class-rh-scripts-and-styles.php',
	'class-rh-talks.php',
	'class-rh-posts.php',
	'class-rh-comments.php',
);
foreach ( $files_to_require as $filename ) {
	$file = get_template_directory() . '/functions/' . $filename;
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}
