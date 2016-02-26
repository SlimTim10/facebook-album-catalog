<?php
/*
Plugin Name: Facebook Album Catalog
Description: Display a catalog that is dynamically linked to a Facebook album.
Version:     1.0.0
Author:      SlimTim10
Author URI:  https://github.com/SlimTim10
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

require_once __DIR__ . '/class-facebook-album-catalog.php';

function admin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include __DIR__ . '/admin-options.php';
}

function admin_register_settings() {
	register_setting('facebook-album-catalog', 'app_id');
	register_setting('facebook-album-catalog', 'app_secret');
	register_setting('facebook-album-catalog', 'page_id');
}

function admin_options_menu() {
	add_options_page('Facebook Album Catalog Settings', 'Facebook Album Catalog', 'manage_options', 'facebook-album-catalog', 'admin_options');
	add_action('admin_init', 'admin_register_settings');
}

add_action('admin_menu', 'admin_options_menu');

function init_catalog() {
	$catalog = new FacebookAlbumCatalog();

	// Configure through admin panel
	$catalog->fb['app_id'] = get_option('app_id');
	$catalog->fb['app_secret'] = get_option('app_secret');
	$catalog->fb['page_id'] = get_option('page_id');

	return $catalog;
}

function facebook_album_catalog_show($atts) {
	$a = shortcode_atts(array(
		'id' => '0',
		'album' => '',
	), $atts, 'facebook_album_catalog' );

	$catalog = init_catalog();
	$catalog->getAlbum($a['album']);

	return $catalog->html;
}
add_shortcode('facebook_album_catalog', 'facebook_album_catalog_show');

function register_plugin_styles() {
	wp_register_style( 'facebook-album-catalog', plugins_url( 'facebook-album-catalog/css/catalog.css' ) );
	wp_enqueue_style( 'facebook-album-catalog' );
}
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
