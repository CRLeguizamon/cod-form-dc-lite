<?php
/**
 * Design Settings Class
 *
 * @package     Modal_MODALCODF_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MODALCODF_Settings_Design extends MODALCODF_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'design';
        $this->title = esc_html__('Diseño', 'modal-cod-form');
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
        // Obtener páginas
        $pages = get_pages();
        $pages_options = array(
            '' => esc_html__('Ninguna', 'modal-cod-form')
        );
        foreach ($pages as $page) {
            $pages_options[$page->ID] = $page->post_title;
        }

        return array(
            array(
                'title' => esc_html__('Configuraciones de Diseño del Formulario', 'modal-cod-form'),
                'type'  => 'title',
                'desc'  => esc_html__('Personaliza el formulario de pago según tus necesidades y mejora la experiencia del cliente.', 'modal-cod-form'),
                'id'    => 'cod_form_general_section_title'
            ),
            array(
                'title'    => esc_html__('URL de la Imagen superior', 'modal-cod-form'),
                'desc'     => esc_html__('Proporciona la URL de la imagen que se mostrará en la parte superior de la ventana modal del formulario de pago. Ideal para mostrar un logotipo o un banner promocional.', 'modal-cod-form'),
                'id'       => 'cod_form_image_url_top',
                'type'     => 'media_upload',
                'default'  => '',
                'css'      => 'min-width:350px;',
                'desc_tip' => true,
            ),
            array(
                'title'    => esc_html__('URL de la Imagen inferior', 'modal-cod-form'),
                'desc'     => esc_html__('Proporciona la URL de la imagen que se mostrará en la parte inferior de la ventana modal del formulario de pago.', 'modal-cod-form'),
                'id'       => 'cod_form_image_url_bottom',
                'type'     => 'media_upload',
                'default'  => '',
                'css'      => 'min-width:350px;',
                'desc_tip' => true,
            ),
            array(
                'title'    => esc_html__('Título de la Ventana Modal', 'modal-cod-form'),
                'desc'     => esc_html__('Cambia el título que aparece en la parte superior de la ventana modal del formulario de pago. Ejemplo por defecto: "Pago contra entrega".', 'modal-cod-form'),
                'id'       => 'cod_form_modal_title',
                'type'     => 'text',
                'default'  => esc_html__('Pago contra entrega', 'modal-cod-form')
            ),
            array(
                'title'    => esc_html__('Tamaño del Título', 'modal-cod-form'),
                'desc'     => esc_html__('Valor en píxeles', 'modal-cod-form'),
                'id'       => 'cod_form_modal_title_size',
                'type'     => 'number',
                'default'  => '18',
                'css'      => 'width: 100px;',
            ),
            array(
                'title'    => esc_html__('Color de resaltado', 'modal-cod-form'),
                'desc'     => esc_html__('Este color se usará en los elementos resaltados del formulario: descuentos, ofertas de cantidad, entre otros.', 'modal-cod-form'),
                'id'       => 'cod_form_highlight_color',
                'type'     => 'color',
                'default'  => '#1d6740',
                'css'      => 'width: 100px;',
            ),
            array(
                'title'    => esc_html__('Deshabilitar Labels del Formulario', 'modal-cod-form'),
                'desc'     => esc_html__('Marca esta opción si deseas ocultar las etiquetas de los campos del formulario.', 'modal-cod-form'),
                'id'       => 'cod_form_disable_labels',
                'type'     => 'checkbox',
                'default'  => 'no'
            ),
            array(
                'title'    => esc_html__('Habilitar vista horizontal', 'modal-cod-form'),
                'desc'     => esc_html__('Marca esta opción si quieres que las etiquetas se muestren al lado de los campos del formulario', 'modal-cod-form'),
                'id'       => 'cod_form_horizontal_layout',
                'type'     => 'checkbox',
                'default'  => 'no'
            ),
            array(
                'title'    => esc_html__('Desactivar campos del formulario', 'modal-cod-form'),
                'desc'     => esc_html__('Selecciona los campos que deseas deshabilitar en el formulario de pago. Esto te permitirá simplificar el proceso de compra y enfocarte en los campos realmente necesarios.', 'modal-cod-form'),
                'id'       => 'cod_form_disable_form_fields',
                'type'     => 'multiselect',
                'class'    => 'wc-enhanced-select',
                'css'      => 'min-width: 350px;',
                'options'  => array(
                    'email' => esc_html__('Correo Electrónico', 'modal-cod-form'),
                    'address_2' => esc_html__('Línea de Dirección 2', 'modal-cod-form'),
                    'order_comments' => esc_html__('Notas del Pedido', 'modal-cod-form'),
                    'terms' => esc_html__('Checkbox de Términos y Condiciones', 'modal-cod-form')
                ),
                'default'  => array()
            ),
            array(
                'title'    => esc_html__('Texto del Checkbox de Términos y Condiciones', 'modal-cod-form'),
                'desc'     => esc_html__('Personaliza el texto que acompaña al checkbox de términos y condiciones en el formulario de pago.', 'modal-cod-form'),
                'id'       => 'cod_form_terms_checkbox_text',
                'type'     => 'text',
                'default'  => esc_html__('Acepto los', 'modal-cod-form')
            ),
            array(
                'title'    => esc_html__('Texto del Enlace de Términos y Condiciones', 'modal-cod-form'),
                'desc'     => esc_html__('Define el texto que aparecerá junto al enlace de términos y condiciones. Deja en blanco si no quieres mostrar ningún texto.', 'modal-cod-form'),
                'id'       => 'cod_form_terms_checkbox_link_text',
                'type'     => 'text',
                'default'  => 'términos y condiciones'
            ),
            array(
                'title'    => esc_html__('Enlace de Términos y Condiciones', 'modal-cod-form'),
                'desc'     => esc_html__('Selecciona la página que contiene los términos y condiciones. Deja en blanco si no deseas mostrar un enlace.', 'modal-cod-form'),
                'id'       => 'cod_form_terms_checkbox_link',
                'type'     => 'select',
                'options'  => $pages_options,
                'default'  => ''
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_general_section_end'
            )
        );
    }
} 