<?php
/**
 * General Settings Class
 *
 * @package     Modal_CODL_Form
 * @since       4.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Settings_General extends CODL_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'general';
        $this->title = esc_html__('General', 'cod-form-dc-lite');
        parent::__construct();
    }

    /**
     * Inicializa la sección
     */
    protected function init() {
        add_action('woocommerce_admin_field_cod_form_status', array($this, 'render_status_field'));
    }

    /**
     * Obtiene las configuraciones
     */
    public function get_settings() {
        // Obtener estados de los pedidos
        $order_statuses = wc_get_order_statuses();
        $options = array('default' => esc_html__('Por defecto', 'cod-form-dc-lite'));

        foreach ($order_statuses as $key => $value) {
            $cleaned_value = str_replace('wc-', '', $key);
            $options[$cleaned_value] = $value;
        }

        return array(
            array(
                'title' => esc_html__('Configuraciones Generales del Formulario', 'cod-form-dc-lite'),
                'type'  => 'title',
                'desc'  => esc_html__('Configura las opciones generales del formulario de pago.', 'cod-form-dc-lite'),
                'id'    => 'cod_form_general_section_title'
            ),
            array(
                'title'    => esc_html__('Seleccionar País', 'cod-form-dc-lite'),
                'desc'     => esc_html__('Elige si deseas que el país base de la tienda sea seleccionado automáticamente o si prefieres habilitar la selección manual de país en el formulario de pago.', 'cod-form-dc-lite'),
                'id'       => 'cod_form_country_selection',
                'type'     => 'radio',
                'options'  => array(
                    'base_country' => esc_html__('Usar país base de la tienda', 'cod-form-dc-lite'),
                    'enable_select' => esc_html__('Habilitar selección de país en el formulario (No funciona con ciudades y departamentos)', 'cod-form-dc-lite')
                ),
                'default'  => 'base_country'
            ),
            array(
                'title'    => esc_html__('Ignorar Métodos de Envío de WooCommerce', 'cod-form-dc-lite'),
                'desc'     => esc_html__('Marca esta opción si deseas que el sistema ignore los métodos de envío de WooCommerce y siempre utilice Envío Gratis.', 'cod-form-dc-lite'),
                'id'       => 'cod_form_ignore_wc_shipping',
                'type'     => 'checkbox',
                'default'  => 'no'
            ),
            array(
                'title'    => esc_html__('Requerir Correo Electrónico', 'cod-form-dc-lite'),
                'desc'     => esc_html__('Marca esta casilla si deseas que el campo de correo electrónico sea obligatorio para completar el pedido.', 'cod-form-dc-lite'),
                'id'       => 'cod_form_email_mandatory',
                'type'     => 'checkbox',
                'default'  => false
            ),
            array(
                'title'    => esc_html__('Desactivar Selección de Cantidad', 'cod-form-dc-lite'),
                'desc'     => esc_html__('Activa esta opción para deshabilitar la selección de cantidad en el formulario de pago, permitiendo solo la compra de un artículo por pedido.', 'cod-form-dc-lite'),
                'id'       => 'cod_form_disable_quantity_selection',
                'type'     => 'checkbox',
                'default'  => 'no'
            ),
            array(
                'title'    => esc_html__('Eliminar productos del carrito antes de agregar otro', 'cod-form-dc-lite'),
                'desc'     => esc_html__('Asegura que el formulario tenga solo un producto al momento de comprar.', 'cod-form-dc-lite'),
                'id'       => 'cod_form_delete_products_cart_before_order',
                'type'     => 'checkbox',
                'default'  => 'no'
            ),
            array(
                'title'    => esc_html__('Estado del pedido', 'cod-form-dc-lite'),
                'desc'     => esc_html__('Selecciona el estado del pedido que deseas que se asigne en WooCommerce al crear un nuevo pedido con el formulario de pago.', 'cod-form-dc-lite'),
                'id'       => 'cod_form_order_status',
                'type'     => 'select',
                'options'  => $options,
                'default'  => 'on-hold'
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_general_section_end'
            )
        );
    }

    /**
     * Renderiza el campo de estado personalizado
     */
    public function render_status_field($field) {
        $current_value = get_option($field['id'], $field['default']);
        ?>
        <tr>
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title']); ?></label>
            </th>
            <td class="forminp">
                <fieldset>
                    <label>
                        <input type="radio" 
                               name="<?php echo esc_attr($field['id']); ?>" 
                               value="enabled" 
                               <?php checked($current_value, 'enabled'); ?>>
                        <?php esc_html_e('Habilitado', 'cod-form-dc-lite'); ?>
                    </label>
                    <br>
                    <label>
                        <input type="radio" 
                               name="<?php echo esc_attr($field['id']); ?>" 
                               value="disabled" 
                               <?php checked($current_value, 'disabled'); ?>>
                        <?php esc_html_e('Deshabilitado', 'cod-form-dc-lite'); ?>
                    </label>
                </fieldset>
                <?php if (!empty($field['desc'])) : ?>
                    <p class="description"><?php echo esc_html($field['desc']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
} 