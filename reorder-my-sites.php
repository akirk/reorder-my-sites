<?php
/**
 * Plugin Name: Reorder My Sites
 * Plugin URI: http://judenware.com/projects/wordpress/
 * Description: Reorders the My Sites dropdown menu in the Admin Bar alphabetically. It keeps the main blog at the top.
 * Author: ericjuden, akirk
 * Version: 1.2.2
 * Author URI: https://github.com/akirk/reorder-my-sites/
 * Network: true
 *
 * @package reorder-my-sites
 */

/**
 * Reorder Sites class
 */
class Reorder_My_Sites {
	function __construct() {
		if ( is_multisite() ) {
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ) );
		}
	}

	function admin_bar_menu() {
		global $wp_admin_bar;

		$blog_names = array();
		$sites = $wp_admin_bar->user->blogs;
		foreach ( $sites as $site_id => $site ) {
			$blog_names[ $site_id ] = strtoupper( $site->blogname );
		}

		// Remove main blog from list...we want that to show at the top.
		unset( $blog_names[1] );

		// Order by name.
		asort( $blog_names );

		// Create new array.
		$wp_admin_bar->user->blogs = array();

		// Add main blog back in to list.
		if ( isset( $sites[1] ) ) {

			$wp_admin_bar->user->blogs[1] = $sites[1];
		}

		// Add others back in alphabetically.
		foreach ( $blog_names as $site_id => $name ) {
			$wp_admin_bar->user->blogs[ $site_id ] = $sites[ $site_id ];
		}
	}
}

$reorder_my_sites = new Reorder_My_Sites();

