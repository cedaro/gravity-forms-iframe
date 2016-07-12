<?php
/**
 * Deprecated functions.
 *
 * Functions in this file are scheduled to be removed in the near future, but
 * are maintained here during a transition period to help prevent fatal errors
 * in case they have been called directly.
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0
 * @since     2.0.0
 */

/**
 * Absolute path to the plugin directory.
 *
 * @since 1.0.0
 * @var string GFIFRAME_DIR Plugin path.
 */
define( 'GFIFRAME_DIR', gfiframe()->get_path() );

/**
 * URL to the plugin directory.
 *
 * @since 1.0.0
 * @var string GFIFRAME_URI Plugin URL.
 */
define( 'GFIFRAME_URI', gfiframe()->get_url() );

/**
 * The main plugin class.
 *
 * @package GravityFormsIframe
 * @author Brady Vercher <brady@blazersix.com>
 * @since 1.0.0
 */
class GravityFormsIframe {
	/**
	 * Retrieve the main GravityFormsIframe_Addon instance.
	 *
	 * @since 1.0.0
	 *
	 * @return GravityFormsIframe
	 */
	public static function instance() {
		return gfiframe();
	}

	/**
	 * Constructor method to initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {}
}
