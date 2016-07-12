<?php
/**
 * Gravity Forms Iframe Add-on
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Gravity Forms Iframe Add-on
 * Plugin URI:  https://github.com/cedaro/gravity-forms-iframe
 * Description: Easily embed Gravity Forms in an auto-resizing iframe on external sites.
 * Version:     2.0.0
 * Author:      Cedaro
 * Author URI:  http://www.cedaro.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gravity-forms-iframe
 * Domain Path: /languages
 */

/**
 * Autoloader callback.
 *
 * Converts a class name to a file path and requires it if it exists.
 *
 * @since 2.0.0
 *
 * @param string $class Class name.
 */
function gfiframe_autoloader( $class ) {
	if ( 0 !== strpos( $class, 'GravityFormsIframe_' ) ) {
		return;
	}

	$file  = dirname( __FILE__ ) . '/classes/';
	$file .= str_replace( array( 'GravityFormsIframe_', '_' ), array( '', '/' ), $class );
	$file .= '.php';

	if ( file_exists( $file ) ) {
		require_once( $file );
	}
}
spl_autoload_register( 'gfiframe_autoloader' );

/**
 * Retrieve the main plugin instance.
 *
 * @since 2.0.0
 *
 * @return GravityFormsIframe_Plugin
 */
function gfiframe() {
	static $instance;

	if ( null === $instance ) {
		$instance = new GravityFormsIframe_Plugin();
	}

	return $instance;
}

$gfiframe = gfiframe()
	->set_basename( plugin_basename( __FILE__ ) )
	->set_directory( plugin_dir_path( __FILE__ ) )
	->set_file( __FILE__ )
	->set_slug( 'gravity-forms-iframe' )
	->set_url( plugin_dir_url( __FILE__ ) )
	->register_hooks( new GravityFormsIframe_Provider_Setup() )
	->register_hooks( new GravityFormsIframe_Provider_I18n() );

/**
 * Include helper functions and backward compatibility files.
 */
include( $gfiframe->get_path( 'includes/deprecated.php' ) );
include( $gfiframe->get_path( 'includes/functions.php' ) );

/**
 * Load the plugin.
 */
add_action( 'plugins_loaded', array( $gfiframe, 'load_plugin' ) );
