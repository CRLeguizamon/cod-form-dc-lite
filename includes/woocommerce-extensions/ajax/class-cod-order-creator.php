<?php

/**
 * Clase para manejar la creación de órdenes COD
 *
 * @package CODL_Form_WC_DC
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Order_Creator {
    
    /**
     * Crear orden estándar de WooCommerce
     */
    public function create_standard_order() {
        // Validar y procesar datos comunes
        $order_data = $this->validate_and_process_order_data();
        if ($order_data === false) {
            return; // Error ya manejado en la función auxiliar
        }

        // Crear orden base
        $order = $this->create_base_order($order_data);

        // Añadir productos del carrito
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $variation_id = isset($cart_item['variation_id']) ? $cart_item['variation_id'] : 0;
            $quantity = $cart_item['quantity'];

            if ($variation_id) {
                $order->add_product(wc_get_product($variation_id), $quantity, array(
                    'variation' => $cart_item['variation'],
                ));
            } else {
                $order->add_product(wc_get_product($product_id), $quantity);
            }
        }

        // Añadir el método de envío como ítem separado
        $shipping_rate = new WC_Shipping_Rate('custom_rate', $order_data['shipping_label'], $order_data['shipping_cost'], array(), 'flat_rate');
        $order->add_shipping($shipping_rate);
        
        // Recalcular totales
        $order->calculate_totals();

        // Finalizar orden
        $this->finalize_order($order);
    }
    
    /**
     * Función auxiliar para validar y procesar datos comunes de las órdenes
     */
    private function validate_and_process_order_data() {
        // Verificar nonce de seguridad
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'cod_form_nonce')) {
            wp_send_json_error(array(
                'message' => esc_html__('Security verification failed', 'cod-form-dc-lite'),
                'error_redirect_url' => home_url()
            ));
            return false;
        }

        // Sanitizar y validar campos requeridos
        $required_fields = array(
            'first_name' => sanitize_text_field(wp_unslash($_POST['first_name'] ?? '')),
            'last_name' => sanitize_text_field(wp_unslash($_POST['last_name'] ?? '')),
            'phone' => sanitize_text_field(wp_unslash($_POST['phone'] ?? '')),
            'address' => sanitize_text_field(wp_unslash($_POST['address'] ?? '')),
            'state' => sanitize_text_field(wp_unslash($_POST['state'] ?? '')),
            'city' => sanitize_text_field(wp_unslash($_POST['city'] ?? ''))
        );

        // Verificar que ningún campo requerido esté vacío
        foreach ($required_fields as $field => $value) {
            if (empty($value)) {
                $error_redirect_url = get_option('cod_form_error_redirect_url', home_url());
                /* translators: %s: nombre del campo del formulario que es requerido */
                $error_message = sprintf(esc_html__('Campo %s es requerido', 'cod-form-dc-lite'), $field);
                wp_send_json_error(array(
                    'message' => $error_message,
                    'error_redirect_url' => $error_redirect_url
                ));
                return false;
            }
        }

        // Check if cart is not empty
        if (empty(WC()->cart->get_cart())) {
            wc_add_notice(esc_html__('No hay productos en el carrito de compra', 'cod-form-dc-lite'), 'error');
            return false;
        }

        // Preparar datos de la orden con campos opcionales
        $order_data = array_merge($required_fields, array(
            'address_2' => isset($_POST['address_2']) ? sanitize_text_field(wp_unslash($_POST['address_2'])) : '',
            'district' => isset($_POST['district']) ? sanitize_text_field(wp_unslash($_POST['district'])) : '',
            'email' => isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '',
            'shipping_method' => isset($_POST['shipping_method']) ? sanitize_text_field(wp_unslash($_POST['shipping_method'])) : '',
            'shipping_code' => isset($_POST['shipping_code']) ? sanitize_text_field(wp_unslash($_POST['shipping_code'])) : '',
            'country' => WC()->countries->get_base_country(),
            'order_comments' => isset($_POST['order_comments']) ? sanitize_text_field(wp_unslash($_POST['order_comments'])) : '',
            'terms_checkbox' => isset($_POST['terms_checkbox']) ? rest_sanitize_boolean(sanitize_text_field(wp_unslash($_POST['terms_checkbox']))) : false
        ));

        // Validate and get shipping data
        if ($order_data['shipping_method'] === 'free_shipping') {
            $order_data['shipping_cost'] = 0;
            $order_data['shipping_label'] = esc_html__('Envío Gratis', 'cod-form-dc-lite');
        } else {
            $shipping_data = validate_shipping_code($order_data['shipping_code']);
            if ($shipping_data === false) {
                $error_redirect_url = get_option('cod_form_error_redirect_url', home_url());
                wp_send_json_error(array(
                    'message' => esc_html__('Invalid shipping data', 'cod-form-dc-lite'),
                    'error_redirect_url' => $error_redirect_url
                ));
                return false;
            }
            $order_data['shipping_cost'] = $shipping_data['cost'];
            $order_data['shipping_label'] = $shipping_data['label'];
        }

        return $order_data;
    }

    /**
     * Función auxiliar para crear la orden base
     */
    private function create_base_order($order_data) {
        $order = wc_create_order();

        // Set billing details
        $order->set_address(array(
            'first_name' => $order_data['first_name'],
            'last_name'  => $order_data['last_name'],
            'phone'      => $order_data['phone'],
            'address_1'  => $order_data['address'],
            'address_2'  => $order_data['address_2'],
            'country'    => $order_data['country'],
            'state'      => $order_data['state'],
            'city'       => $order_data['city']
        ), 'billing');

        $order->set_billing_email($order_data['email']);

        // Set shipping details
        $order->set_address(array(
            'first_name' => $order_data['first_name'],
            'last_name'  => $order_data['last_name'],
            'address_1'  => $order_data['address'],
            'address_2'  => $order_data['address_2'],
            'country'    => $order_data['country'],
            'state'      => $order_data['state'],
            'city'       => $order_data['city']
        ), 'shipping');

        // Add district if provided
        if (!empty($order_data['district'])) {
            $order->update_meta_data('billing_district', $order_data['district']);
            $order->update_meta_data('shipping_district', $order_data['district']);
        }

        // Add order notes if provided
        if (!empty($order_data['order_comments'])) {
            $order->add_order_note($order_data['order_comments']);
        }

        // Add terms checkbox status if provided
        if ($order_data['terms_checkbox']) {
            $order->update_meta_data('_terms_checkbox', 'checked');
        }

        // Set payment method
        $order->set_payment_method('cod');

        return $order;
    }

    /**
     * Función auxiliar para finalizar la orden
     */
    private function finalize_order($order) {
        // Get the order status from settings
        $order_status = get_option('cod_form_order_status', 'on-hold');
        
        // Update order status if it is not 'default'
        if ($order_status !== 'default') {
            $order->update_status($order_status, esc_html__('Awaiting cash on delivery', 'cod-form-dc-lite'));
        }
        
        // Save the order
        $order->save();
        
        // Get order total
        $order_total = $order->get_total();

        do_action('cod_order_finalized', $order->get_id(), $order);
        
        // Clear the cart
        WC()->cart->empty_cart();
        
        // Build default WooCommerce order received URL
        $order_id = $order->get_id();
        $order_key = $order->get_order_key();
        
        $success_redirect_url = add_query_arg(
            array(
                'order-received' => $order_id,
                'key' => $order_key,
            ),
            wc_get_checkout_url()
        );
        
        // Return success response
        wp_send_json_success(array(
            'message' => esc_html__('Order created successfully', 'cod-form-dc-lite'),
            'total_amount' => $order_total,
            'success_redirect_url' => $success_redirect_url
        ));
    }
} 