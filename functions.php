<?php

/**
 * This file adds functions to the Sorriso D'Ambrosio 
 *
 * @package sorrisodambrosio
 * @author  Gianfranco Navas Fernandini
 * @license GNU General Public License v2 or later
 * @link    https://www.sorrisodambrosio.it/
 */

namespace Sorrisodambrosio;

/**
 * Set up theme defaults and register various WordPress features.
 */
function setup()
{

	// Enqueue editor styles and fonts.
	add_editor_style('style.css');

	// Remove core block patterns.
	remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', __NAMESPACE__ . '\setup');


/**
 * Enqueue styles.
 */
function enqueue_style_sheet()
{

	// Get the modification time of your custom stylesheet
	$custom_stylesheet_version = filemtime(get_template_directory() . '/dist/main.bundle.css');

	// Enqueue your custom stylesheet with file modification time as version
	wp_enqueue_style('custom-stylesheet', get_template_directory_uri() . '/dist/main.bundle.css', array(), $custom_stylesheet_version);
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_style_sheet');


/**
 * Add Dashicons for use with block styles.
 */
function enqueue_block_dashicons()
{
	wp_enqueue_style('dashicons');
}
add_action('enqueue_block_assets', __NAMESPACE__ . '\enqueue_block_dashicons');


/**
 * Add block style variations.
 */
function register_block_styles()
{

	$block_styles = array(
		'core/button'                    => array(
			'secondary-button' => __('Secondary', 'sorrisodambrosio'),
		),
		'core/list'                      => array(
			'list-check'        => __('Check', 'sorrisodambrosio'),
			'list-check-circle' => __('Check Circle', 'sorrisodambrosio'),
			'list-boxed'        => __('Boxed', 'sorrisodambrosio'),
		),
		'core/query-pagination-next'     => array(
			'wp-block-button__link' => __('Button', 'sorrisodambrosio'),
		),
		'core/query-pagination-previous' => array(
			'wp-block-button__link' => __('Button', 'sorrisodambrosio'),
		),
		'core/code'                      => array(
			'dark-code' => __('Dark', 'sorrisodambrosio'),
		),
		'core/cover'                     => array(
			'blur-image-less' => __('Blur Image Less', 'sorrisodambrosio'),
			'blur-image-more' => __('Blur Image More', 'sorrisodambrosio'),
			'rounded-cover'   => __('Rounded', 'sorrisodambrosio'),
		),
		'core/column'                    => array(
			'column-box-shadow' => __('Box Shadow', 'sorrisodambrosio'),
		),
		'core/post-excerpt'              => array(
			'excerpt-truncate-2' => __('Truncate 2 Lines', 'sorrisodambrosio'),
			'excerpt-truncate-3' => __('Truncate 3 Lines', 'sorrisodambrosio'),
			'excerpt-truncate-4' => __('Truncate 4 Lines', 'sorrisodambrosio'),
		),
		'core/group'                     => array(
			'column-box-shadow' => __('Box Shadow', 'sorrisodambrosio'),
		),
		'core/separator'                 => array(
			'separator-dotted' => __('Dotted', 'sorrisodambrosio'),
			'separator-thin'   => __('Thin', 'sorrisodambrosio'),
		),
		'core/image'                     => array(
			'rounded-full' => __('Rounded Full', 'sorrisodambrosio'),
			'media-boxed'  => __('Boxed', 'sorrisodambrosio'),
		),
		'core/preformatted'              => array(
			'preformatted-dark' => __('Dark Style', 'sorrisodambrosio'),
		),
		'core/post-terms'                => array(
			'term-button' => __('Button Style', 'sorrisodambrosio'),
		),
		'core/video'                     => array(
			'media-boxed' => __('Boxed', 'sorrisodambrosio'),
		),
	);

	foreach ($block_styles as $block => $styles) {
		foreach ($styles as $style_name => $style_label) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action('init', __NAMESPACE__ . '\register_block_styles');


/**
 * Load custom block styles only when the block is used.
 */
function enqueue_custom_block_styles()
{

	// Scan our styles folder to locate block styles.
	$files = glob(get_template_directory() . '/assets/styles/*.css');

	foreach ($files as $file) {

		// Get the filename and core block name.
		$filename   = basename($file, '.css');
		$block_name = str_replace('core-', 'core/', $filename);

		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => "sorrisodambrosio-block-{$filename}",
				'src'    => get_theme_file_uri("assets/styles/{$filename}.css"),
				'path'   => get_theme_file_path("assets/styles/{$filename}.css"),
			)
		);
	}
}
add_action('init', __NAMESPACE__ . '\enqueue_custom_block_styles');


/**
 * Register pattern categories.
 */
function pattern_categories()
{

	$block_pattern_categories = array(
		'sorrisodambrosio/card'           => array(
			'label' => __('Cards', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/call-to-action' => array(
			'label' => __('Call To Action', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/features'       => array(
			'label' => __('Features', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/hero'           => array(
			'label' => __('Hero', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/pages'          => array(
			'label' => __('Pages', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/posts'          => array(
			'label' => __('Posts', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/pricing'        => array(
			'label' => __('Pricing', 'sorrisodambrosio'),
		),
		'sorrisodambrosio/testimonial'    => array(
			'label' => __('Testimonials', 'sorrisodambrosio'),
		),
	);

	foreach ($block_pattern_categories as $name => $properties) {
		register_block_pattern_category($name, $properties);
	}
}
add_action('init', __NAMESPACE__ . '\pattern_categories', 9);


/**
 * Remove last separator on blog/archive if no pagination exists.
 */
function is_paginated()
{
	global $wp_query;
	if ($wp_query->max_num_pages < 2) {
		echo '<style>.blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .archive .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .search .wp-block-post-template .wp-block-post:last-child .wp-block-post-excerpt + .wp-block-separator { display: none; }</style>';
	}
}
add_action('wp_head', __NAMESPACE__ . '\is_paginated');


/**
 * Add a Sidebar template part area
 */
function template_part_areas(array $areas)
{
	$areas[] = array(
		'area'        => 'sidebar',
		'area_tag'    => 'section',
		'label'       => __('Sidebar', 'sorrisodambrosio'),
		'description' => __('The Sidebar template defines a page area that can be found on the Page (With Sidebar) template.', 'sorrisodambrosio'),
		'icon'        => 'sidebar',
	);

	return $areas;
}
add_filter('default_wp_template_part_areas', __NAMESPACE__ . '\template_part_areas');

function enqueue_script_sheet()
{
	// Enqueue your main custom script
	wp_enqueue_script('your-main-script-handle', get_template_directory_uri() . '/dist/main.bundle.js', array(), wp_get_theme()->get('Version'), true);

	// Enqueue anime.js library
	wp_enqueue_script('anime-js', 'https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js', array(), '2.0.2', true);
}

// Enqueue custom scripts
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_script_sheet');
