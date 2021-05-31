<?php
/**
 * Gravity Forms Iframe add-on settings.
 *
 * @package   GravityFormsIframe
 * @copyright Copyright (c) 2016, Cedaro, LLC
 * @license   GPL-2.0+
 * @since     2.0.0
 */

/**
 * The main add-on class.
 *
 * Extends the Gravity Forms add-on class. Functionality that needs access to
 * the add-on API should be encapsulated since most methods and properties are
 * protected.
 *
 * @package GravityFormsIframe
 * @since   2.0.0
 */
class GravityFormsIframe_Addon extends GFAddOn {
	/**
	 * Plugin instance.
	 *
	 * @since 2.0.0
	 * @var GravityFormsIframe_Plugin
	 */
	protected $plugin;

	/**
	 * Members plugin integration.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $_capabilities = array( 'gravityforms_iframe' );

	/**
	 * Form settings capability.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $_capabilities_form_settings = 'gravityforms_iframe';

	/**
	 * Class constructor for setting up the add-on.
	 *
	 * @since 2.0.0
	 * @see GFAddOn
	 *
	 * @param GravityFormsIframe_Plugin $plugin Main plugin instance.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		$this->_title       = esc_html__( 'Gravity Forms Iframe Add-On', 'gravity-forms-iframe' );
		$this->_short_title = esc_html__( 'Iframe', 'gravity-forms-iframe' );
		$this->_version     = '2.0.2';
		$this->_slug        = 'gfiframe';
		$this->_path        = $this->plugin->get_basename();
		$this->_full_path   = $this->plugin->get_file();

		parent::__construct();
	}

	/**
	 * Register add-on scripts.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'gfiframe-settings',
				'src'     => $this->plugin->get_url( 'assets/scripts/settings.js' ),
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => 'gfiframe',
					),
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Declare the sections and fields for the iframe add-on.
	 *
	 * @since 2.0.0
	 *
	 * @param array $form Form data.
	 * @return array
	 */
	public function form_settings_fields( $form ) {
		return array(
			array(
				'title'       => esc_html__( 'Iframe Settings', 'gravity-forms-iframe' ),
				'description' => '',
				'fields'      => array(
					array(
						'label'   => esc_html__( 'Enable embedding', 'gravity-forms-iframe' ),
						'type'    => 'checkbox',
						'name'    => 'is_enabled',
						'onclick' => '',
						'tooltip' => '',
						'choices' => array(
							array(
								'label' => esc_html__( 'Allow this form to be embedded in an iframe', 'gravity-forms-iframe' ),
								'name'  => 'is_enabled',
							),
						),
					),
					array(
						'label'   => esc_html__( 'Display title', 'gravity-forms-iframe' ),
						'type'    => 'checkbox',
						'name'    => 'display_title',
						'onclick' => '',
						'tooltip' => '',
						'choices' => array(
							array(
								'label' => esc_html__( 'Display title', 'gravity-forms-iframe' ),
								'name'  => 'display_title',
							),
						),
					),
					array(
						'label'   => esc_html__( 'Display description', 'gravity-forms-iframe' ),
						'type'    => 'checkbox',
						'name'    => 'display_description',
						'tooltip' => '',
						'onclick' => '',
						'choices' => array(
							array(
								'label' => esc_html__( 'Display description', 'gravity-forms-iframe' ),
								'name'  => 'display_description',
							),
						),
					),
					array(
						'label'   => esc_html__( 'Embed Code', 'gravity-forms-iframe' ),
						'type'    => 'iframe_embed_code',
						'name'    => 'embed_code',
						'tooltip' => '',
						'class'   => 'fieldwidth-3 field-height-3',
					),
				),
			),
		);
	}

	/**
	 * Render a field for displaying the code to embed a form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $field Field array containing the configuration options of this field.
	 * @param bool  $echo  Whether the field should be displayed.
	 * @return string
	 */
	public function settings_iframe_embed_code( $field, $echo = true ) {
		$form = $this->get_current_form();

		$field['type'] = 'iframe_embed_code';

		$attributes   = $this->get_field_attributes( $field );
		$attributes[] = 'readonly="readonly"';
		$attributes[] = 'onfocus="this.select();"';
		$attributes[] = 'style="cursor: auto; pointer-events: auto"';

		$iframe_url = home_url( '/gfembed/' );
		$iframe_url = add_query_arg( 'f', $form['id'], $iframe_url );
		$iframe_url = preg_replace( '#^http(s)?:#', '', $iframe_url );

		// Relative protocol.
		$script_url = preg_replace( '#^http(s)?:#', '', $this->plugin->get_url( 'assets/scripts/gfembed.min.js' ) );

		$value  = '<iframe src="' . esc_url( $iframe_url ) . '" width="100%" height="500" frameBorder="0" class="gfiframe"></iframe>' . "\n";
		$value .= '<script src="' . esc_url( $script_url ) . '" type="text/javascript"></script>';

		$tooltip = '';
		if ( isset( $choice['tooltip'] ) ) {
			$tooltip = gform_tooltip( $choice['tooltip'], rgar( $choice, 'tooltip_class'), true );
		}

		$html = '<textarea ' . implode( ' ', $attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}
}
