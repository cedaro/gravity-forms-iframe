<?php
/**
 * Plugin setup.
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 * @since     2.0.0
 */

/**
 * Plugin setup class.
 *
 * @package GravityFormsIframe
 * @since   2.0.0
 */
class GravityFormsIframe_Provider_Setup extends GravityFormsIframe_AbstractProvider {
	/**
	 * Register hooks.
	 *
	 * @since 2.0.0
	 */
	public function register_hooks() {
		register_activation_hook( $this->plugin->get_file(),   array( $this, 'activate' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_flush_rewrite_rules' ) );
	}

	/**
	 * Flush the rewrite rules after activation.
	 *
	 * @since 2.0.0
	 */
	public function maybe_flush_rewrite_rules() {
		if ( ! is_network_admin() && 'yes' == get_option( 'gfiframe_flush_rewrite_rules' ) ) {
			update_option( 'gfiframe_flush_rewrite_rules', 'no' );
			flush_rewrite_rules();
		}
	}

	/**
	 * Activation routine.
	 *
	 * @since 2.0.0
	 */
	public function activate() {
		update_option( 'gfiframe_flush_rewrite_rules', 'yes' );
	}
}
