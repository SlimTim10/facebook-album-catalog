<?php
/*
Plugin Name: Facebook Album Catalog
Description: Display a catalog that is dynamically linked to a Facebook album.
Version:     1.0
Author:      SlimTim10
Author URI:  https://github.com/SlimTim10
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

require_once __DIR__ . '/class-facebook-album-catalog.php';
require_once __DIR__ . '/lib/facebook-php-sdk-v4-5.0-dev/src/Facebook/autoload.php';

function admin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include __DIR__ . '/admin-options.php';
}

function admin_register_settings() {
	register_setting('facebook-album-catalog', 'app_id');
	register_setting('facebook-album-catalog', 'app_secret');
	register_setting('facebook-album-catalog', 'access_token');
}

function admin_options_menu() {
	add_options_page('Facebook Album Catalog Settings', 'Facebook Album Catalog', 'manage_options', 'facebook-album-catalog', 'admin_options');
	add_action('admin_init', 'admin_register_settings');
}

add_action('admin_menu', 'admin_options_menu');

function create_catalog() {
	$catalog = new FacebookAlbumCatalog();

	// Configure through admin panel
	$app_id = get_option('app_id');
	$app_secret = get_option('app_secret');
	$access_token = get_option('access_token');

	$catalog->fb = new Facebook\Facebook([
		'app_id' => $app_id,
		'app_secret' => $app_secret,
		'default_graph_version' => 'v2.4',
		'default_access_token' => $access_token,
	]);

	$catalog->albumName('Cute Animals');

	return $catalog;
}

function facebook_album_catalog_show ($atts) {
	$atts = shortcode_atts( array(
		'id' => '0',
	), $atts, 'facebook_album_catalog' );

	$catalog = create_catalog();

	$ret = '<pre>' . var_export($catalog->test, true) . '</pre>';
	
	return $ret;
}
add_shortcode('facebook_album_catalog', 'facebook_album_catalog_show');