<?php
/**
 * Main plugin.
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 * @since     2.0.0
 */

/**
 * Main plugin class.
 *
 * @package GravityFormsIframe
 * @since   2.0.0
 */
class GravityFormsIframe_Plugin extends GravityFormsIframe_AbstractPlugin {
	/**
	 * Load the plugin.
	 *
	 * @since 2.0.0
	 */
	public function load_plugin() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		add_action( 'init',              array( $this, 'register_rewrite_rules' ) );
		add_filter( 'query_vars',        array( $this, 'query_vars' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_action( 'wp_footer',         array( $this, 'wp_footer' ) );

		// Load the Gravity Forms add-on framework and iframe add-on.
		GFForms::include_addon_framework();
		$this->addon = new GravityFormsIframe_Addon( $this );
	}

	/**
	 * Add custom rewrite rules.
	 *
	 * @since 1.0.0
	 */
	public function register_rewrite_rules() {
		// The PHP extension is deprecated.
		add_rewrite_rule( '(gfembed(.php)?)/?$', 'index.php?gfiframe=$matches[1]', 'top' );
	}

	/**
	 * Whitelist custom query vars.
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars Allowed query vars.
	 * @return array
	 */
	public function query_vars( $vars ) {
		$vars[] = 'gfiframe';
		return $vars;
	}

	/**
	 * Handle requests for form iframes.
	 *
	 * @since 1.0.0
	 */
	public function template_redirect() {
		global $wp;

		if ( empty( $wp->query_vars['gfiframe'] ) || ( 'gfembed' != $wp->query_vars['gfiframe'] && 'gfembed.php' != $wp->query_vars['gfiframe'] ) ) {
			return;
		}

		$form_id = null;
		if ( ! empty( $_GET['f'] ) ) {
			$form_id = absint( $_GET['f'] );
		} else {
			// The request needs an 'f' query arg with the form id.
			wp_die( esc_html__( 'Invalid form id.', 'gravity-forms-iframe' ) );
		}

		$form = GFFormsModel::get_form_meta( $form_id );
		$settings = $this->addon->get_form_settings( $form );

		// Make sure the form can be embedded.
		if ( empty( $settings['is_enabled'] ) || ! $settings['is_enabled'] ) {
			wp_die( esc_html__( 'Embedding is disabled for this form.', 'gravity-forms-iframe' ) );
		}

		// Disable the toolbar in case the form is embedded on the same domain.
		add_filter( 'show_admin_bar', '__return_false', 100 );

		require_once( GFCommon::get_base_path() . '/form_display.php' );

		// Settings may be overridden in the query string (querystring -> form settings -> default).
		$args = wp_parse_args( $_GET, array(
			'dt' => empty( $settings['display_title'] ) ? false : (bool) $settings['display_title'],
			'dd' => empty( $settings['display_description'] ) ? false : (bool) $settings['display_description'],
		) );

		// @todo Need to convert query string values to boolean.
		$display_title       = (bool) $args['dt'];
		$display_description = (bool) $args['dd'];

		unset( $args );
		unset( $settings );

		// Templates can be customized in parent or child themes.
		$templates = array(
			'gravity-forms-iframe-' . $form_id . '.php',
			'gravity-forms-iframe.php',
		);

		$template = gfiframe_locate_template( $templates );
		include( $template );
		exit;
	}

	/**
	 * Add a script to send the parent the iframe's size via postMessage.
	 *
	 * @since 1.0.0
	 */
	public function wp_footer() {
		if ( ! is_gfiframe_template() && ! apply_filters( 'gfiframe_print_resize_ping_script', false ) ) {
			return;
		}
		?>
		<script>
		(function( window, undefined ) {
			window.addEventListener( 'message', function( e ) {
				if ( 'size' === e.data.message ) {
					e.source.postMessage({
						message: 'size',
						// @link https://stackoverflow.com/a/1147768
						height: getBodyHeight(),
						index: e.data.index,
						width: document.body.scrollWidth
					}, e.origin );
				}
			});

			function getBodyHeight() {
				var styles = getComputedStyle( document.body );
				return parseInt( styles.height, 10 ) + parseInt( styles.marginTop, 10 ) + parseInt( styles.marginBottom );
			}
		})( this );
		</script>
		<?php

		do_action( 'gfiframe_form_footer' );
	}
}
