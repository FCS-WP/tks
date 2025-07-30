<?php

/**
 * Child-Theme functions and definitions
 */

// Load rtl.css because it is not autoloaded from the child theme
if (! function_exists('tint_child_load_rtl')) {
	add_filter('wp_enqueue_scripts', 'tint_child_load_rtl', 3000);
	function tint_child_load_rtl()
	{
		if (is_rtl()) {
			wp_enqueue_style('tint-style-rtl', get_template_directory_uri() . '/rtl.css');
		}
	}
}

/*
 * Define Variables
 */
if (!defined('THEME_DIR'))
	define('THEME_DIR', get_template_directory());
if (!defined('THEME_URL'))
	define('THEME_URL', get_template_directory_uri());


/*
 * Include framework files
 */
foreach (glob(THEME_DIR . '-child' . "/includes/*.php") as $file_name) {
	require_once($file_name);
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
