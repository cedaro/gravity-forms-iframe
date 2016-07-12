<?php
/**
 * Common plugin functionality.
 *
 * @package   GravityFormsIframe
 * @since     2.0.0
 * @copyright Copyright (c) 2016 Cedaro, LLC
 * @license   GPL-2.0+
 */

/**
 * Abstract plugin class.
 *
 * @package GravityFormsIframe
 * @since   2.0.0
 */
abstract class GravityFormsIframe_AbstractPlugin {
	/**
	 * Plugin basename.
	 *
	 * Ex: plugin-name/plugin-name.php
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $basename;

	/**
	 * Absolute path to the main plugin directory.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $directory;

	/**
	 * Absolute path to the main plugin file.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $file;

	/**
	 * Plugin identifier.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $slug;

	/**
	 * URL to the main plugin directory.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $url;

	/**
	 * Retrieve the relative path to the main plugin file from the plugin directory.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_basename() {
		return $this->basename;
	}

	/**
	 * Set the plugin basename.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $basename Relative path from the main plugin directory.
	 * @return string
	 */
	public function set_basename( $basename ) {
		$this->basename = $basename;
		return $this;
	}

	/**
	 * Retrieve the plugin directory.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_directory() {
		return $this->directory;
	}

	/**
	 * Set the plugin's directory.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $directory Absolute path to the main plugin directory.
	 * @return $this
	 */
	public function set_directory( $directory ) {
		$this->directory = rtrim( $directory, '/' ) . '/';
		return $this;
	}

	/**
	 * Retrieve the path to a file in the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $path Optional. Path relative to the plugin root.
	 * @return string
	 */
	public function get_path( $path = '' ) {
		return $this->directory . ltrim( $path, '/' );
	}

	/**
	 * Retrieve the absolute path for the main plugin file.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Set the path to the main plugin file.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $file Absolute path to the main plugin file.
	 * @return $this
	 */
	public function set_file( $file ) {
		$this->file = $file;
		return $this;
	}

	/**
	 * Retrieve the plugin indentifier.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Set the plugin identifier.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $slug Plugin identifier.
	 * @return $this
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * Retrieve the URL for a file in the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $path Optional. Path relative to the plugin root.
	 * @return string
	 */
	public function get_url( $path = '' ) {
		return $this->url . ltrim( $path, '/' );
	}

	/**
	 * Set the URL for plugin directory root.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $url URL to the root of the plugin directory.
	 * @return $this
	 */
	public function set_url( $url ) {
		$this->url = rtrim( $url, '/' ) . '/';
		return $this;
	}

	/**
	 * Register a hook provider.
	 *
	 * @since 2.0.0
	 *
	 * @param  object $provider Hook provider.
	 * @return $this
	 */
	public function register_hooks( $provider ) {
		if ( method_exists( $provider, 'set_plugin' ) ) {
			$provider->set_plugin( $this );
		}

		$provider->register_hooks();
		return $this;
	}
}
