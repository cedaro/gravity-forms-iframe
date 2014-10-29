<?php
/**
 * Gravity Forms Iframe Add-On
 *
 * @since 1.0.0
 *
 * @package GFIframe
 * @author Brady Vercher <brady@blazersix.com>
 * @license GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Gravity Forms Iframe Add-On
 * Plugin URI: https://github.com/bradyvercher/gravity-forms-iframe
 * Description: Easily embed Gravity Forms in an auto-resizing iframe on external sites.
 * Version: 1.0.1
 * Author: Blazer Six, Inc.
 * Author URI: http://www.blazersix.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gravity-forms-iframe
 * Domain Path: /languages
 * GitHub Plugin URI: https://github.com/bradyvercher/gravity-forms-iframe
 * GitHub Branch: master
 */

/**
 * Absolute path to the plugin directory.
 *
 * @since 1.0.0
 * @var string GFIFRAME_DIR Plugin path.
 */
define( 'GFIFRAME_DIR', plugin_dir_path( __FILE__ ) );

/**
 * URL to the plugin directory.
 *
 * @since 1.0.0
 * @var string GFIFRAME_URI Plugin URL.
 */
define( 'GFIFRAME_URI', plugin_dir_url( __FILE__ ) );

/**
 * Include helper functions.
 */
require( GFIFRAME_DIR . 'includes/functions.php' );

/**
 * The main plugin class.
 *
 * @package GFIframe
 * @author Brady Vercher <brady@blazersix.com>
 * @since 1.0.0
 */
class GFIframe {
	/**
	 * The main plugin instance.
	 *
	 * @access private
	 * @var GFIframe
	 */
	private static $instance;

	/**
	 * The Gravity Forms Add-on instance.
	 *
	 * @access private
	 * @var GFIframe_Addon
	 */
	private $addon;

	/**
	 * Retrieve the main GFIframe_Addon instance.
	 *
	 * @since 1.0.0
	 *
	 * @return GFIframe
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor method to initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load' ) );
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
	}

	/**
	 * Load the plugin.
	 *
	 * Attaches hooks for intercepting requests for iframed forms and loads the
	 * Gravity Forms add-on framework and iframe add-on.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		self::load_textdomain();

		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_flush_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

		// Load the Gravity Forms add-on framework and iframe add-on.
		GFForms::include_addon_framework();
		require( dirname( __FILE__ ) . '/includes/class-gfiframe-addon.php' );
		$this->addon = new GFIframe_Addon();
	}

	/**
	 * Support localization for the plugin strings.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'gfiframe' );
		load_textdomain( 'gfiframe', WP_LANG_DIR . '/gravity-forms-iframe/' . $locale . '.mo' );
		load_plugin_textdomain( 'gfiframe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Add custom rewrite rules.
	 *
	 * The JavaScript file automatically embeds an iframe form and resizes it.
	 *
	 * @since 1.0.0
	 */
	public function init() {
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

		$form_id = empty( $_GET['f'] ) ? null : absint( $_GET['f'] );

		// The request needs an 'f' query arg with the form id.
		if ( empty( $form_id ) ) {
			wp_die( __( 'Invalid form id.', 'gravity-forms-iframe' ) );
		}

		$form = GFFormsModel::get_form_meta( $form_id );
		$settings = $this->addon->get_gfiframe_form_settings( $form );

		// Make sure the form can be embedded.
		if ( empty( $settings['is_enabled'] ) || ! $settings['is_enabled'] ) {
			wp_die( __( 'Embedding is disabled for this form.', 'gravity-forms-iframe' ) );
		}

		// Disable the toolbar in case the form is embedded on the same domain.
		show_admin_bar( false );

		require_once( GFCommon::get_base_path() . '/form_display.php' );

		// Settings may be overridden in the query string. querystring -> form settings -> default
		$args = wp_parse_args( $_GET, array(
			'dt' => empty( $settings['display_title'] ) ? false : (bool) $settings['display_title'],
			'dd' => empty( $settings['display_description'] ) ? false : (bool) $settings['display_description'],
		) );

		// @todo Need to convert query string values to boolean.
		$display_title       = (bool) $args['dt'];
		$display_description = (bool) $args['dd'];

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
		<script type="text/javascript">
		( function( window, $, undefined ) {
			var $document = $( document );

			$( window ).on( 'message', function( e ) {
				if ( 0 === e.originalEvent.data.indexOf( 'size?' ) ) {
					var index = e.originalEvent.data.replace( 'size?', '' ),
						// size:index:width:height
						message = 'size:' + index + ',' + document.body.scrollWidth + ',' + $document.height();

					e.originalEvent.source.postMessage( message, e.originalEvent.origin );
				}
			});
		} )( this, jQuery );
		</script>
		<?php

		do_action( 'gfiframe_form_footer' );
	}

	/**
	 * Flush the rewrite rules after activation.
	 *
	 * @since 1.0.0
	 */
	public function maybe_flush_rewrite_rules() {
		if ( ! is_network_admin() && 'yes' == get_option( 'gfiframe_flush_rewrite_rules' ) ) {
			update_option( 'gfiframe_flush_rewrite_rules', 'no' );
			flush_rewrite_rules();
		}
	}

	/**
	 * Activation method.
	 *
	 * Occurs too late to flush rewrite rules, so set an option to flush the
	 * rewrite rules on the next request.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		update_option( 'gfiframe_flush_rewrite_rules', 'yes' );
	}
}

GFIframe::instance();
