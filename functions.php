<?php
/**
 * Include other files needed by the theme
 *
 * @package RH
 */

// Require Composer dependencies
$autoload_file = get_template_directory() . '/vendor/autoload.php';
if ( file_exists( $autoload_file ) ) {
	require_once $autoload_file;
}

$files_to_require = array(
	'debugging.php',

	'class-rh-admin.php',
	// 'class-rh-cdn.php',
	'class-rh-helpers.php',
	'class-rh-svg.php',
	'class-rh-pagination.php',
	'class-rh-menus.php',
	'class-rh-breadcrumbs.php',
	'class-rh-media.php',
	'class-rh-scripts-and-styles.php',
	'class-rh-search.php',
	'class-rh-post-type-archives.php',
	'class-rh-posts.php',
	'class-rh-talks.php',

	'class-rh-blocks.php',
	'class-rh-cli.php',
);
foreach ( $files_to_require as $filename ) {
	$file = get_template_directory() . '/functions/' . $filename;
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

// Autoload block classes
$the_path  = get_template_directory() . '/blocks/';
$directory = new RecursiveDirectoryIterator( $the_path );
$filter    = new RecursiveCallbackFilterIterator(
	$directory,
	function ( $current ) {
		// Skip hidden files and directories.
		if ( $current->getFilename()[0] === '.' ) {
			return false;
		}
		if ( $current->getExtension() === '' || $current->getExtension() === 'php' ) {
			return true;
		}
		return false;
	}
);
$iterator  = new RecursiveIteratorIterator( $filter );
foreach ( $iterator as $file ) {
	$the_file = $file->getPathname();
	if ( file_exists( $the_file ) ) {
		require_once $the_file;
	}
}


/**
 * Add the styleguide directory to the known directories Sprig should look
 * for Twig files to render
 *
 * @param  array $paths Places Twig should look for Twig files
 * @return array         Modified paths
 */
function filter_sprig_roots( $paths = array() ) {
	$paths[] = get_template_directory() . '/styleguide';

	// Add every directory in the /blocks/ directory to the possible path for a Twig file
	$iter = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator(
			get_template_directory() . '/blocks/',
			RecursiveDirectoryIterator::SKIP_DOTS
		),
		RecursiveIteratorIterator::SELF_FIRST,
		RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
	);
	foreach ( $iter as $path => $dir ) {
		if ( $dir->isDir() ) {
			$paths[] = $path;
		}
	}
	return $paths;
}
add_filter( 'sprig/roots', 'filter_sprig_roots' );

/**
 * Make additional PHP functions available as Twig filters
 *
 * @param array $filters The Twig filters to modify
 */
function filter_sprig_twig_filters( $filters = array() ) {
	$filters['sanitize_title'] = 'sanitize_title';
	return $filters;
}
add_filter( 'sprig/twig/filters', 'filter_sprig_twig_filters' );

/**
 * Make additional PHP functions available as Twig functions
 *
 * @param  array $functions The Twig functions to modify
 */
function filter_sprig_twig_functions( $functions = array() ) {
	$functions['get_language_attributes']    = 'get_language_attributes';
	$functions['get_template_directory_uri'] = 'get_template_directory_uri';
	return $functions;
}
add_filter( 'sprig/twig/functions', 'filter_sprig_twig_functions' );

/**
 * Add support for the title tag as of WordPress version 4.1
 *
 * @link https://codex.wordpress.org/Title_Tag#Adding_Theme_Support
 */
add_action(
	'init',
	function () {
		add_theme_support( 'title-tag' );
	}
);
