<?php
/**
 * Gravity Forms Iframe add-on settings.
 *
 * @since 1.0.0
 *
 * @package GFIframe
 * @author Brady Vercher <brady@blazersix.com>
 * @license GPL-2.0+
 */

/**
 * The main add-on class.
 *
 * Extends the Gravity Forms add-on class. Functionality that needs access to
 * the add-on API should be encapsulated since most methods and properties are
 * protected.
 *
 * @package GFIframe
 * @author Brady Vercher <brady@blazersix.com>
 * @since 1.0.0
 */
class GFIframe_Addon extends GFAddOn {
	/**
	 * Class constructor for setting up the add-on.
	 *
	 * @access private
	 * @since 1.0.0
	 * @see GFAddOn
	 */
	public function __construct() {
		$this->_title       = __( 'Gravity Forms Iframe Add-On', 'gravity-forms-iframe' );
		$this->_short_title = __( 'Iframe', 'gravity-forms-iframe' );
		$this->_version     = '1.0.0';
		$this->_slug        = 'gfiframe';
		$this->_path        = 'gravity-forms-iframe/gravity-forms-iframe.php';
		$this->_full_path   = dirname( dirname( __FILE__ ) ) . '/gravity-forms-iframe.php';

		parent::__construct();
	}

	/**
	 * Retrieve form settings.
	 *
	 * Public wrapper for the protected API method.
	 *
	 * @since 1.0.0
	 * @see GFAddon::get_form_settings()
	 *
	 * @param array|int $form Form ID or data.
	 * @return array
	 */
	public function get_gfiframe_form_settings( $form ) {
		$form = is_int( $form ) ? GFFormsModel::get_form_meta( $form ) : $form;
		return $this->get_form_settings( $form );
	}

	/**
	 * Register add-on scripts.
	 *
	 * @access protected
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function scripts() {
		$scripts = array(
			array(
				'handle'  => 'gfiframe-settings',
				'src'     => $this->get_base_url() . '/assets/scripts/settings.js',
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
	 * @access protected
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function form_settings_fields( $form ) {
		return array(
			array(
				'title'       => __( 'Iframe Settings', 'gravity-forms-iframe' ),
				'description' => '',
				'fields'      => array(
					array(
						'label'   => __( 'Enable embedding', 'gravity-forms-iframe' ),
						'type'    => 'checkbox',
						'name'    => 'is_enabled',
						'onclick' => '',
						'tooltip' => '',
						'choices' => array(
							array(
								'label' => __( 'Allow this form to be embedded in an iframe', 'gravity-forms-iframe' ),
								'name'  => 'is_enabled',
							),
						),
					),
					array(
						'label'   => __( 'Display title', 'gravity-forms-iframe' ),
						'type'    => 'checkbox',
						'name'    => 'display_title',
						'onclick' => '',
						'tooltip' => '',
						'choices' => array(
							array(
								'label' => __( 'Display title', 'gravity-forms-iframe' ),
								'name'  => 'display_title',
							),
						),
					),
					array(
						'label'   => __( 'Display description', 'gravity-forms-iframe' ),
						'type'    => 'checkbox',
						'name'    => 'display_description',
						'tooltip' => '',
						'onclick' => '',
						'choices' => array(
							array(
								'label' => __( 'Display description', 'gravity-forms-iframe' ),
								'name'  => 'display_description',
							),
						),
					),
					array(
						'label'   => __( 'Embed Code', 'gravity-forms-iframe' ),
						'type'    => 'gfiframe_embed_code',
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
	 * @since 1.0.0
     *
     * @param array $field Field array containing the configuration options of this field.
     * @param bool $echo Whether the field should be displayed.
     * @return string
     */
    protected function settings_gfiframe_embed_code( $field, $echo = true ) {
		$form = $this->get_current_form();

		$field['type'] = 'gfiframe_embed_code';
		$attributes = $this->get_field_attributes( $field );
		$attributes[] = 'readonly="readonly"';
		$attributes[] = 'onfocus="this.select();"';

		$src = home_url( '/gfembed/' );
		$src = add_query_arg( 'f', $form['id'], $src );
		$src = preg_replace( '#^http(s)?:#', '', $src );

		// Relative protocol.
		$plugin_uri = preg_replace( '#^http(s)?:#', '', GFIFRAME_URI );

		$value  = '<iframe src="' . esc_url( $src ) . '" width="100%" height="500" frameBorder="0" class="gfiframe"></iframe>' . "\n";
		$value .= '<script src="' . esc_url( $plugin_uri . 'assets/scripts/gfembed.min.js' ) . '" type="text/javascript"></script>';
		$tooltip =  isset( $choice['tooltip'] ) ? gform_tooltip( $choice['tooltip'], rgar( $choice, 'tooltip_class'), true ) : '';

		$html = '<textarea ' . implode( ' ', $attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

		if ( $echo ) {
			echo $html;
		}

		return $html;
    }
}
