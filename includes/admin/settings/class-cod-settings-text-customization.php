<?php
/**
 * Text Customization Settings Class
 *
 * @package     Modal_MODALCODF_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MODALCODF_Settings_Text_Customization extends MODALCODF_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'custom_fields';
        $this->title = esc_html__('Personalización de Textos', 'modal-cod-form');
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
                'title'     => esc_html__('Personalización de Textos del Formulario', 'modal-cod-form'),
                'type'     => 'title',
                'desc'     => esc_html__('Personaliza los textos que aparecen en el formulario de pago. Puedes modificar tanto las etiquetas como los textos de ayuda que se muestran dentro de cada campo.', 'modal-cod-form'),
                'id'       => 'cod_form_custom_field_section_title'
            ),
            
            // Nombre
            array(
                'title' => esc_html__('Campo: Nombre', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de nombre.', 'modal-cod-form'),
                'id'   => 'cod_form_first_name_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de nombre.', 'modal-cod-form'),
                'id'   => 'cod_form_first_name_label',
                'default' => 'Nombre'
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_first_name_placeholder',
                'default' => 'Nombre',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_first_name_section_end'
            ),
            
            // Apellido
            array(
                'title' => esc_html__('Campo: Apellido', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de apellido.', 'modal-cod-form'),
                'id'   => 'cod_form_last_name_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de apellido.', 'modal-cod-form'),
                'id'   => 'cod_form_last_name_label',
                'default' => 'Apellido',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_last_name_placeholder',
                'default' => 'Apellido',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_last_name_section_end'
            ),
            
            // Teléfono
            array(
                'title' => esc_html__('Campo: Teléfono', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de teléfono.', 'modal-cod-form'),
                'id'   => 'cod_form_phone_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de teléfono.', 'modal-cod-form'),
                'id'   => 'cod_form_phone_label',
                'default' => 'Teléfono',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_phone_placeholder',
                'default' => 'Número de celular',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_phone_section_end'
            ),
            
            // Departamento
            array(
                'title' => esc_html__('Campo: Departamento', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de departamento.', 'modal-cod-form'),
                'id'   => 'cod_form_state_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de departamento.', 'modal-cod-form'),
                'id'   => 'cod_form_state_label',
                'default' => 'Departamento',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_state_placeholder',
                'default' => 'Departamento',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_state_section_end'
            ),
            
            // Ciudad
            array(
                'title' => esc_html__('Campo: Ciudad', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de ciudad.', 'modal-cod-form'),
                'id'   => 'cod_form_city_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de ciudad.', 'modal-cod-form'),
                'id'   => 'cod_form_city_label',
                'default' => 'Ciudad',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_city_placeholder',
                'default' => 'Ciudad',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_city_section_end'
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_district_placeholder',
                'default' => 'Distrito',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_district_section_end'
            ),
            
            // Dirección
            array(
                'title' => esc_html__('Campo: Dirección', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de dirección.', 'modal-cod-form'),
                'id'   => 'cod_form_address_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de dirección.', 'modal-cod-form'),
                'id'   => 'cod_form_address_label',
                'default' => 'Dirección',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_address_placeholder',
                'default' => 'Dirección',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_address_section_end'
            ),
            
            // Dirección 2
            array(
                'title' => esc_html__('Campo: Dirección 2', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de dirección secundaria.', 'modal-cod-form'),
                'id'   => 'cod_form_address_2_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de dirección secundaria.', 'modal-cod-form'),
                'id'   => 'cod_form_address_2_label',
                'default' => 'Dirección 2',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_address_2_placeholder',
                'default' => 'Ejemplo: Apartamento 400',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_address_2_section_end'
            ),
            
            // Correo electrónico
            array(
                'title' => esc_html__('Campo: Correo electrónico', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de correo electrónico.', 'modal-cod-form'),
                'id'   => 'cod_form_email_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de correo electrónico.', 'modal-cod-form'),
                'id'   => 'cod_form_email_label',
                'default' => 'Correo electrónico',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_email_placeholder',
                'default' => 'Correo electrónico',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_email_section_end'
            ),
            
            // Notas de la orden
            array(
                'title' => esc_html__('Campo: Notas de la orden', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura los textos relacionados con el campo de notas de la orden.', 'modal-cod-form'),
                'id'   => 'cod_form_order_notes_section'
            ),
            array(
                'title' => esc_html__('Etiqueta del campo', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece antes del campo de notas de la orden.', 'modal-cod-form'),
                'id'   => 'cod_form_order_notes_label',
                'default' => 'Notas de la orden',
            ),
            array(
                'title' => esc_html__('Texto de ayuda', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece dentro del campo cuando está vacío.', 'modal-cod-form'),
                'id'   => 'cod_form_order_notes_placeholder',
                'default' => 'Debes anunciarte antes de...',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_order_notes_section_end'
            ),
            
            // Botón de pago
            array(
                'title' => esc_html__('Botón de pago', 'modal-cod-form'),
                'type' => 'title',
                'desc' => esc_html__('Configura el texto que aparece en el botón de completar orden.', 'modal-cod-form'),
                'id'   => 'cod_form_button_section'
            ),
            array(
                'title' => esc_html__('Texto del botón', 'modal-cod-form'),
                'type' => 'text',
                'desc' => esc_html__('Texto que aparece en el botón de completar orden.', 'modal-cod-form'),
                'id'   => 'cod_form_button_payment_text',
                'default' => 'Completar orden',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_button_section_end'
            ),
            
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_custom_field_section_end',
            )
        );
    }
} 