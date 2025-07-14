<?php
/**
 * Product Fields Class
 *
 * @package     Modal_CODL_Form
 * @since       4.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Product_Fields {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_cod_disable_field'));
        add_action('woocommerce_process_product_meta', array($this, 'save_cod_disable_field'));
    }

    /**
     * Agrega el campo personalizado al producto
     */
    public function add_cod_disable_field() {
        global $woocommerce, $post;

        echo '<div class="options_group">';
        
        woocommerce_wp_checkbox(array(
            'id'          => '_cod_disable_button',
            'label'       => esc_html__('Deshabilitar botón COD', 'cod-form-dc-lite'),
            'description' => esc_html__('Marque esta opción para deshabilitar el botón COD en este producto', 'cod-form-dc-lite'),
            'desc_tip'    => true,
        ));

        echo '</div>';
    }

    /**
     * Guarda el valor del campo personalizado
     */
    public function save_cod_disable_field($post_id) {
        $cod_disable_button = isset($_POST['_cod_disable_button']) ? 'yes' : 'no';
        update_post_meta($post_id, '_cod_disable_button', $cod_disable_button);
    }

    /**
     * Verifica si el botón COD está deshabilitado para un producto
     */
    public static function is_cod_disabled($product_id) {
        return get_post_meta($product_id, '_cod_disable_button', true) === 'yes';
    }
} 