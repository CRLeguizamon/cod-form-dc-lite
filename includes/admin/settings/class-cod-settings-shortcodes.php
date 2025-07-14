<?php
/**
 * Shortcodes Settings Class
 *
 * @package     Modal_CODL_Form
 * @since       4.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Settings_Shortcodes extends CODL_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'shortcodes';
        $this->title = esc_html__('Shortcodes', 'cod-form-dc-lite');
        parent::__construct();
    }

    /**
     * Inicializa la sección
     */
    protected function init() {
        // No se requieren hooks específicos
    }

    /**
     * Obtiene las configuraciones
     */
    public function get_settings() {
        return array(
            array(
                'title' => esc_html__('Configuraciones de Shortcodes', 'cod-form-dc-lite'),
                'type'  => 'title',
                'desc'  => esc_html__('En esta sección podrás usar códigos personalizados para agregar funcionalidades a otras partes de tu tienda.', 'cod-form-dc-lite'),
                'id'    => 'cod_form_shortcodes_section_title'
            ),
            array(
                'title'    => esc_html__('Shortcode para agregar botón de "Agregar al carrito" en cualquier parte del sitio', 'cod-form-dc-lite'),
                'desc'     => esc_html__('<p>Usa este shortcode en páginas como landing pages o cualquier otra página personalizada. Debes pasar el ID del producto en el atributo `id`. Para obtener el ID del producto, ve al administrador de WordPress y busca el número en la URL del producto, como `post=123`.</p>
                <p><strong style="color: red;">IMPORTANTE:</strong> Si el producto es variable y tiene ofertas de cantidad activas, el formulario mostrará el selector de variaciones. Si el producto variable no tiene ofertas de cantidad configuradas, deberás usar el ID de la variación específica y no se mostrará el selector de variaciones.</p>', 'cod-form-dc-lite'),
                'id'       => 'cod_form_custom_add_to_cart_button_shortcode',
                'type'     => 'text',
                'default'  => '[custom_add_to_cart_button id="123"]',
                'custom_attributes' => array(
                    'readonly' => 'readonly',
                    'onclick' => 'this.select();'
                )
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_shortcodes_section_end'
            )
        );
    }
} 