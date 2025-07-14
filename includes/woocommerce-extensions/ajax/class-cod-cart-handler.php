<?php

/**
 * Clase para manejar operaciones del carrito COD
 *
 * @package CODL_Form_WC_DC
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Cart_Handler {
    
    /**
     * Agregar producto al carrito
     */
    public function add_to_cart() {
        // Obtener y validar datos
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        // Delete product in cart
        $cod_form_delete_products_cart_before_order = get_option('cod_form_delete_products_cart_before_order', 'no');
        if($cod_form_delete_products_cart_before_order !== 'no'){
            if ( WC()->cart->get_cart_contents_count() > 0 ) {
                WC()->cart->empty_cart();
            }
        }   
        
        if ($product_id) {
            if ($variation_id) {
                $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            } else {
                // Aquí añadir el código
                $product = wc_get_product($product_id);
                if ($product && $product->is_type('variable')) {
                    // Obtener la primera variación disponible
                    $variations = $product->get_available_variations();
                    if (!empty($variations)) {
                        $variation_id = $variations[0]['variation_id'];
                        $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
                    } else {
                        wp_send_json_error(array('message' => esc_html__('No hay variaciones disponibles para este producto', 'cod-form-dc-lite')));
                        return;
                    }
                } else {
                    $added = WC()->cart->add_to_cart($product_id, $quantity);
                }
                // Aquí termina el código
            }

            if ($added) {
                wp_send_json_success(array(
                    'message' => esc_html__('Product added to cart', 'cod-form-dc-lite'),
                    'cart_hash' => WC()->cart->get_cart_hash(),
                    'cart_count' => WC()->cart->get_cart_contents_count()
                ));
            } else {
                wp_send_json_error(array('message' => esc_html__('Failed to add product to cart', 'cod-form-dc-lite')));
            }
        } else {
            wp_send_json_error(array('message' => esc_html__('Invalid product', 'cod-form-dc-lite')));
        }
    }
    
    /**
     * Remover producto del carrito
     */
    public function remove_product() {
        if (!isset($_POST['cart_item_key'])) {
            wp_send_json_error(['message' => 'No cart item key provided.']);
        }

        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        $cart = WC()->cart->get_cart();
        if (isset($cart[$cart_item_key])) {
            $current_quantity = $cart[$cart_item_key]['quantity'];
            if ($current_quantity > $quantity) {
                // Disminuir la cantidad de productos en lugar de eliminarlos por completo
                WC()->cart->set_quantity($cart_item_key, $current_quantity - $quantity);
            } else {
                // Eliminar el producto si la cantidad es menor o igual a la cantidad en el carrito
                WC()->cart->remove_cart_item($cart_item_key);
            }

            $cart_items = WC()->cart->get_cart();
            ob_start();
            include(CODL_DC_TEM . 'table-content.php');
            $new_cart_content = ob_get_clean();

            $subtotal = WC()->cart->get_subtotal();
            $total = WC()->cart->get_total('edit');

            wp_send_json_success([
                'message' => 'Product removed successfully.',
                'new_cart_content' => $new_cart_content,
                'subtotal' => $subtotal,
                'total' => $total,
                'currency_symbol' => html_entity_decode(get_woocommerce_currency_symbol()), // Decodificar el símbolo de moneda
                'cart_count' => WC()->cart->get_cart_contents_count(),
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to remove product from cart.']);
        }
    }
} 