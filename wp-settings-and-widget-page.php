<?php

/* 
* Plugin Name: Wp Settings and Widget Page
* Plugin URI: http://www.wordpress.org/wp-settings-and-widget-page
* Description: Settings & Widget page
* Version: 1.0.0 
* Author: Mayank D.
* Author URI: https://www.wp-settings-widget-page.io 
* License: GPL3 
* Text Domain: wp-settings-and-widget-page
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WP_SETTINGS_VERSION', '2.0.0' );
define( 'WP_SETTINGS__FILE__', __FILE__ );
define( 'WP_SETTINGS_PLUGIN_BASE', plugin_basename( WP_SETTINGS__FILE__ ) );
define( 'WP_SETTINGS_PATH', plugin_dir_path( WP_SETTINGS__FILE__ ) );


/**
 * Load gettext translate for our text domain.
 */
function wp_settings_and_widget_load_plugin() {
	load_plugin_textdomain( 'wp-settings-and-widget-page' );

	require( WP_SETTINGS_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'wp_settings_and_widget_load_plugin' );