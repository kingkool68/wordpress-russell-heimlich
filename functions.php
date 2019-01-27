<?php
/**
 * Various functions
 *
 * @package Russell Heimlich
 */

$files_to_require = array(
	'debugging.php',
	'class-rh-helpers.php',
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

/**
 * Add the styleguide directory to the known directories Sprig should look
 * for Twig files to render
 *
 * @param  array  $paths Places Twig should look for Twig files
 * @return array         Modified paths
 */
function filter_sprig_roots( $paths = array() ) {
	$paths[] = get_template_directory() . '/styleguide';
	return $paths;
}
add_filter( 'sprig/roots', 'filter_sprig_roots' );
