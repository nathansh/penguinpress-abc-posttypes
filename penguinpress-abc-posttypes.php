<?php
/**
 * @package penguinpress-abc-posttypes
 */
/*
Plugin Name: PenguinPress Alphabetize Post Types
Description: Alphabetizes the post type listing in the admin menu
Version: 1.0-alpha
Author: Nathan Shubert-Harbison
Author URI: http://nathansh.com
Text Domain: penguinpress-abc-posttypes
*/

add_action( 'admin_menu', 'penguinpress_abc_posttypes_sort', 999, 1 );

/**
 * Alphabetically sort the post types section of the admin menu
 *
 */
function penguinpress_abc_posttypes_sort() {

	global $menu;

	// Sorted menu items
	$sorted_menu = array();

	// Store separators
	$separators = array();

	/**
	 * First, group menu items by their section
	 *
	 */
	$count = 0;
	foreach ( $menu as $item ) {

		if ( array_pop((array_slice($item, -1))) == 'wp-menu-separator' ) {
			$count++;
			$separators[] = $item;
		} else {
			$sorted_menu[$count][] = $item;
		}

	}

	/**
	 * Sort the second group, the custom post type group
	 *
	 */
	usort($sorted_menu[1], function($a, $b) {
		return $a[0] > $b[0];
	});

	/**
	 * Compress menu back into a flat structure
	 *
	 */
	$flat_menu = array();
	foreach ( $sorted_menu as $section_index => $section ) {

		// Loop through the items in the section
		foreach ( $section as $index => $item ) {

			// Append the item to the flattened menu
			$flat_menu[] = $item;

			// If we're at the end of a section, insert a separator back in
			if ( $index + 1 == count($section) && isset($separators[$section_index]) ) {
				$flat_menu[] = $separators[$section_index];
			}

		}

	}

	$menu = $flat_menu;

}
