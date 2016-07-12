<?php
/**
 * Gravity Forms Iframe Add-On functions.
 *
 * @since 1.0.0
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 * @since     1.0.0
 */

/**
 * Determine if the current request is for an embedded form.
 *
 * @global $wp
 * @since 1.0.0
 *
 * @return bool
 */
function is_gfiframe_template() {
	global $wp;
	return isset( $wp->query_vars['gfiframe'] ) && ( 'gfembed' == $wp->query_vars['gfiframe']  || 'gfembed.php' == $wp->query_vars['gfiframe'] );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. Falls back to the
 * built-in template.
 *
 * @since 1.1.0
 * @see locate_template()
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 * @return string The template path if one is located.
 */
function gfiframe_locate_template( $template_names, $load = false, $require_once = true ) {
	$template = '';

	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name ) {
			continue;
		}

		if ( file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {
			$template = get_stylesheet_directory() . '/' . $template_name;
			break;
		} elseif ( file_exists( get_template_directory() . '/' . $template_name ) ) {
			$template = get_template_directory() . '/' . $template_name;
			break;
		} elseif ( file_exists( gfiframe()->get_path( 'templates/' . $template_name ) ) ) {
			$template = gfiframe()->get_path( 'templates/' . $template_name );
			break;
		}
	}

	if ( $load && ! empty( $template ) ) {
		load_template( $template, $require_once );
	}

	return $template;
}
