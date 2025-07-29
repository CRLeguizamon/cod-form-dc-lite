<?php
/**
 * Payment Button Settings Class
 *
 * @package     Modal_MODALCODF_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MODALCODF_Settings_Payment_Button extends MODALCODF_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'payment_button';
        $this->title = esc_html__('Botón de Pago', 'modal-cod-form');
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
                'title' => esc_html__('Personaliza tu Botón de ordenar', 'modal-cod-form'),
                'type'  => 'title',
                'desc'  => esc_html__('Configura la apariencia y el texto del botón de pago para que coincida con la estética de tu tienda.', 'modal-cod-form'),
                'id'    => 'cod_form_payment_button_section_title'
            ),
            array(
                'title'    => esc_html__('Color de Fondo del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Selecciona un color de fondo que haga destacar tu botón de pago.', 'modal-cod-form'),
                'id'       => 'cod_form_background_color',
                'type'     => 'color',
                'default'  => '#000000'
            ),
            array(
                'title'    => esc_html__('Color del Texto del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Selecciona el color del texto que se mostrará en el botón.', 'modal-cod-form'),
                'id'       => 'cod_form_text_color',
                'type'     => 'color',
                'default'  => '#ffffff'
            ),
            array(
                'title'    => esc_html__('Texto Principal del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Define el texto principal que aparecerá en el botón de pago.', 'modal-cod-form'),
                'id'       => 'cod_form_button_text',
                'type'     => 'text',
                'default'  => esc_html__('Pagar al recibir', 'modal-cod-form')
            ),
            array(
                'title'    => esc_html__('Subtítulo del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Puedes añadir un subtítulo debajo del texto principal para mayor claridad.', 'modal-cod-form'),
                'id'       => 'cod_form_button_subtitle',
                'type'     => 'text',
                'default'  => esc_html__('Pago seguro', 'modal-cod-form')
            ),
            array(
                'title'    => esc_html__('Tamaño del Texto', 'modal-cod-form'),
                'desc'     => esc_html__('Especifica el tamaño del texto en unidades rem (entre 0.8 y 1.3 rem es recomendable).', 'modal-cod-form'),
                'id'       => 'cod_form_text_size',
                'type'     => 'number',
                'default'  => '1',
                'custom_attributes' => array(
                    'step' => '0.1',
                    'min' => '0.8',
                    'max' => '1.3'
                )
            ),
            array(
                'title'    => esc_html__('Ancho del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Elige si el botón tendrá un ancho normal o abarcará todo el ancho disponible.', 'modal-cod-form'),
                'id'       => 'cod_form_button_width',
                'type'     => 'select',
                'options'  => array(
                    'normal' => esc_html__('Normal', 'modal-cod-form'),
                    'full' => esc_html__('Completo (100%)', 'modal-cod-form')
                ),
                'default'  => 'normal'
            ),
            array(
                'title'    => esc_html__('Redondeo de Bordes', 'modal-cod-form'),
                'desc'     => esc_html__('Especifica el redondeo de los bordes del botón en píxeles. (0 para esquinas rectas, 50 para bordes totalmente redondeados).', 'modal-cod-form'),
                'id'       => 'cod_form_border_radius',
                'type'     => 'number',
                'default'  => '0',
                'custom_attributes' => array(
                    'step' => '1',
                    'min' => '0',
                    'max' => '50'
                )
            ),
            array(
                'title'    => esc_html__('Ancho del Borde del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Especifica el grosor del borde del botón en píxeles (de 0 a 15 px).', 'modal-cod-form'),
                'id'       => 'cod_form_border_width',
                'type'     => 'number',
                'default'  => '0',
                'custom_attributes' => array(
                    'step' => '1',
                    'min' => '0',
                    'max' => '15'
                )
            ),
            array(
                'title'    => esc_html__('Color del Borde del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Selecciona el color del borde del botón si has especificado un ancho mayor a 0.', 'modal-cod-form'),
                'id'       => 'cod_form_border_color',
                'type'     => 'color',
                'default'  => '#000000'
            ),
            array(
                'title'    => esc_html__('Sombra del Contenedor del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Añade una sombra sutil alrededor del botón. Un valor entre 0 y 1 rem es ideal.', 'modal-cod-form'),
                'id'       => 'cod_form_box_shadow',
                'type'     => 'number',
                'default'  => '0',
                'custom_attributes' => array(
                    'step' => '0.1',
                    'min' => '0',
                    'max' => '1'
                )
            ),
            array(
                'title'    => esc_html__('Animación del Botón', 'modal-cod-form'),
                'desc'     => esc_html__('Elige una animación para que el botón atraiga la atención del cliente.', 'modal-cod-form'),
                'id'       => 'cod_form_button_animation',
                'type'     => 'select',
                'options'  => array(
                    'none' => esc_html__('Ninguna', 'modal-cod-form'),
                    'shake' => esc_html__('Temblor', 'modal-cod-form'),
                    'bounce' => esc_html__('Rebote', 'modal-cod-form'),
                    'pulse' => esc_html__('Pulso', 'modal-cod-form')
                ),
                'default'  => 'none'
            ),
            array(
                'title'    => esc_html__('Botón Pegajoso', 'modal-cod-form'),
                'desc'     => esc_html__('Marca esta opción si deseas que el botón se mantenga fijo en la parte inferior de la pantalla a partir de una resolución de 767px. (Dispositivos móviles y tablets)', 'modal-cod-form'),
                'id'       => 'cod_form_button_sticky',
                'type'     => 'checkbox',
                'default'  => 'no'
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_payment_button_section_end'
            )
        );
    }
} 