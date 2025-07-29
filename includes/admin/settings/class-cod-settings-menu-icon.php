<?php
/**
 * Menu Icon Settings Class
 *
 * @package     Modal_MODALCODF_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MODALCODF_Settings_Menu_Icon extends MODALCODF_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'menu_icon';
        $this->title = esc_html__('Ícono del carrito', 'modal-cod-form');
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
                'title' => esc_html__('Configuración del Ícono del Carrito', 'modal-cod-form'),
                'type'  => 'title',
                'desc'  => esc_html__('<p>Esta sección te permite personalizar el ícono del carrito que aparece en el menú de navegación de tu tienda. El ícono del carrito es una herramienta útil que:</p>
                              <ul>
                                <li>Muestra rápidamente el número de productos en el carrito</li>
                                <li>Permite a los clientes acceder rápidamente a su carrito de compras</li>
                                <li>Mejora la experiencia de usuario al hacer las compras</li>
                              </ul>
                              <p>Puedes personalizar el color y tamaño del ícono para que combine con el diseño de tu sitio.</p>', 'modal-cod-form'),
                'id'    => 'cod_form_menu_icon_section_title'
            ),
            array(
                'title'    => esc_html__('Color del ícono', 'modal-cod-form'),
                'desc'     => esc_html__('Selecciona el color del ícono del carrito. Algunas sugerencias:
                                <ul>
                                    <li>Usa el color principal de tu marca para mantener la consistencia</li>
                                    <li>Elige un color que contraste bien con el fondo del menú</li>
                                    <li>Considera usar un color que llame la atención sin ser intrusivo</li>
                                </ul>', 'modal-cod-form'),
                'id'       => 'cod_form_icon_color',
                'type'     => 'color',
                'default'  => '#000000'
            ),
            array(
                'title'    => esc_html__('Tamaño del ícono', 'modal-cod-form'),
                'desc'     => esc_html__('Ajusta el tamaño del ícono del carrito en píxeles. Recomendaciones:
                                <ul>
                                    <li>25px - Tamaño estándar, ideal para la mayoría de los menús</li>
                                    <li>30px - Un poco más grande, bueno para menús con mucho espacio</li>
                                    <li>20px - Más compacto, útil para menús estrechos</li>
                                </ul>
                                <p>Ten en cuenta que un tamaño demasiado grande puede afectar el diseño de tu menú.</p>', 'modal-cod-form'),
                'id'       => 'cod_form_icon_max_width',
                'type'     => 'number',
                'default'  => '25',
                'custom_attributes' => array(
                    'min' => '0'
                )
            ),
            array(
                'title'    => esc_html__('Shortcode para el ícono', 'modal-cod-form'),
                'desc'     => esc_html__('<p>Usa este shortcode para mostrar el ícono del carrito en cualquier parte de tu sitio:</p>
                                <code>[cod_menu_cart]</code>
                                <p>Características del shortcode:</p>
                                <ul>
                                    <li>Se puede usar en widgets, páginas o plantillas</li>
                                    <li>Muestra automáticamente el número de productos en el carrito</li>
                                    <li>Se actualiza automáticamente cuando se agregan o eliminan productos</li>
                                </ul>
                                <p>Personalización del color:</p>
                                <p>Puedes cambiar el color del ícono directamente en el shortcode usando el atributo <strong>color</strong>:</p>
                                <code>[cod_menu_cart color="#f2f2f2"]</code>
                                <p>Esto cambiará el color del ícono a <code>#f2f2f2</code> sin afectar la configuración general.</p>', 'modal-cod-form'),
                'id'       => 'cod_form_shortcode_info',
                'type'     => 'text',
                'default'  => '[cod_menu_cart]',
                'custom_attributes' => array(
                    'readonly' => 'readonly'
                )
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_menu_icon_section_end'
            )
        );
    }
} 