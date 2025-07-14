<?php

/**
 * Clase para manejar métodos de envío COD
 *
 * @package CODL_Form_WC_DC
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Shipping_Handler {
    
    /**
     * Obtener métodos de envío disponibles
     */
    public function get_methods() {
        // Verificar nonce para seguridad
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'cod_shipping_methods_nonce')) {
            wp_die(esc_html__('Security check failed', 'cod-form-dc-lite'));
        }
        
        // Verificar que el parámetro 'state' esté presente
        if (isset($_POST['state'])) {
            $state = sanitize_text_field(wp_unslash($_POST['state']));
            
            // Obtener el país
            if (isset($_POST['country'])) {
                $country = sanitize_text_field(wp_unslash($_POST['country']));
            } else {
                // Obtener el país configurado en WooCommerce si no se proporcionó en $_POST
                $default_country = get_option('woocommerce_default_country');
                if (strpos($default_country, ':') !== false) {
                    list($country, $state_code) = explode(':', $default_country);
                } else {
                    $country = $default_country;
                }
            }
            
            // Configurar la dirección para obtener las tarifas de envío
            WC()->customer->set_shipping_state($state);
            WC()->customer->set_shipping_country($country);
            WC()->customer->set_calculated_shipping(true);
            WC()->cart->calculate_shipping();
            
            // Obtener los paquetes de envío y las tarifas
            $packages = WC()->shipping()->get_packages();
            
            $shipping_methods_html = '';
            $first_rate = true; // Variable para controlar el primer radio button

            if (!empty($packages)) {
                foreach ($packages as $i => $package) {
                    if (!empty($package['rates'])) {
                        foreach ($package['rates'] as $rate) {
                            $shipping_methods_html .= '<div class="shipping-method">';
                            $shipping_methods_html .= '<label>';

                            // Agregar el atributo 'checked' al primer radio button
                            $checked = ($first_rate) ? ' checked' : '';
                            $first_rate = false; // Asegurarse de que solo el primero se marque

                            // Generar código cifrado para el envío
                            $shipping_code = generate_shipping_code($rate->id, $rate->cost, $rate->label);

                            $shipping_methods_html .= '<input type="radio" name="shipping_method" value="' . esc_attr($rate->id) . '" data-cost="' . esc_attr($rate->cost) . '" data-shipping-code="' . esc_attr($shipping_code) . '"' . $checked . '>';
                            $shipping_methods_html .= '<span>' . esc_html($rate->label) . ' - ' . wc_price($rate->cost) . '</span>';
                            $shipping_methods_html .= '</label>';
                            $shipping_methods_html .= '</div>';
                        }
                    } else {
                        $shipping_methods_html .= '<p>' . esc_html__('No shipping methods available', 'cod-form-dc-lite') . '</p>';
                    }
                }
            } else {
                $shipping_methods_html .= '<p>' . esc_html__('No shipping packages available', 'cod-form-dc-lite') . '</p>';
            }

            // Definir reglas de kses que permitan atributos data-* para inputs
            $allowed_html = array(
                'div' => array(
                    'class' => array(),
                ),
                'label' => array(),
                'span' => array(),
                'input' => array(
                    'type' => array(),
                    'name' => array(),
                    'value' => array(),
                    'data-cost' => array(),
                    'data-shipping-code' => array(),
                    'checked' => true,
                ),
                'p' => array(),
            );
            
            echo wp_kses($shipping_methods_html, $allowed_html);
        } else {
            echo '<p>' . esc_html__('Invalid request', 'cod-form-dc-lite') . '</p>';
        }
        
        // Importante: Terminar el script para que no se impriman valores adicionales
        wp_die();
    }
} 