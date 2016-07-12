<?php
/**
 * Base hook provider.
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 * @since     2.0.0
 */

/**
 * Base hook provider class.
 *
 * @package GravityFormsIframe
 * @since   2.0.0
 */
abstract class GravityFormsIframe_AbstractProvider {
	/**
	 * Plugin instance.
	 *
	 * @since 2.0.0
	 * @var GravityFormsIframe_Plugin
	 */
	protected $plugin;

	/**
	 * Set a reference to the main plugin instance.
	 *
	 * @since 2.0.0
	 *
	 * @param GravityFormsIframe_Plugin $plugin Main plugin instance.
	 */
	public function set_plugin( $plugin ) {
		$this->plugin = $plugin;
		return $this;
	}

	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	abstract public function register_hooks();
}
