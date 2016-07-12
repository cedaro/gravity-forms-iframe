<?php
/**
 * Internationalization provider.
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 * @since     2.0.0
 */

/**
 * Internationalization provider class.
 *
 * @package GravityFormsIframe
 * @since   2.0.0
 */
class GravityFormsIframe_Provider_I18n extends GravityFormsIframe_AbstractProvider {
	/**
	 * Register hooks.
	 *
	 * Loads the text domain during the `plugins_loaded` action.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {
		if ( did_action( 'plugins_loaded' ) ) {
			$this->load_textdomain();
		} else {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}
	}

	/**
	 * Load the text domain to localize the plugin.
	 *
	 * @since 2.0.0
	 */
	public function load_textdomain() {
		$plugin_rel_path = dirname( $this->plugin->get_basename() ) . '/languages';
		load_plugin_textdomain( $this->plugin->get_slug(), false, $plugin_rel_path );
	}
}
