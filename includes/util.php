<?php 
/**
 * Util functions
 * 
 * Este archivo contiene funciones de utilidad para el plugin.
 */

/**
 * Convierte un string de precio en un número flotante.
 * 
 * Esta función elimina el símbolo de la moneda y cualquier otro carácter no numérico
 * del string de precio y lo convierte en un número flotante.
 * 
 * @param string $price_string El string de precio que se desea convertir.
 * @return float El valor numérico del precio.
 */
function parse_price_string($price_string) {
    // Eliminar el símbolo de la moneda y cualquier otro carácter no numérico
    $price_number = preg_replace('/[^0-9,.]/', '', $price_string);
    
    // Reemplazar las comas y puntos de acuerdo al formato esperado (depende del formato local)
    $price_number = str_replace(',', '', $price_number);
    
    // Convertir a número flotante
    return (float) $price_number;
}

/**
 * Obtiene todos los IDs de productos en el carrito de WooCommerce.
 *
 * @return array Retorna un array con los IDs de productos en el carrito.
 */
function get_cart_product_ids() {
    $cart_product_ids = array();

    if (isset(WC()->cart) && !empty(WC()->cart->get_cart())) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $cart_product_ids[] = $cart_item['product_id'];
        }
    }

    return $cart_product_ids;
}

/**
 * Verifica si hay un único producto diferente en el carrito de WooCommerce.
 *
 * @return bool Retorna true si hay solo un producto único en el carrito, false si hay más de uno.
 */
function is_single_product_in_cart() {
    $cart_product_ids = get_cart_product_ids();

    $unique_product_ids = array_unique($cart_product_ids);
    return count($unique_product_ids) === 1; // Retorna true si hay un único producto
}

/* Cifrar descuento */
function generate_discount_code($discount_type, $discount_amount) {
    $secret_key = D_S_K; // Tu clave secreta

    // Concatenar el tipo y la cantidad del descuento
    $data = $discount_type . '|' . $discount_amount;

    // Crear un hash con HMAC usando la clave secreta
    $hash = hash_hmac('sha256', $data, $secret_key);

    // Cifrar el tipo de descuento, cantidad y el hash en base64
    return base64_encode($discount_type . '|' . $discount_amount . '|' . $hash);
}

/* Descifrar descuento */
function validate_discount_code($encoded_discount_code) {
    $secret_key = D_S_K; // Tu clave secreta

    // Decodificar el código de descuento cifrado
    $decoded = base64_decode($encoded_discount_code);

    if (!$decoded) {
        return false; // El código no es válido
    }

    // Dividir los datos en partes (tipo, cantidad, hash)
    list($discount_type, $discount_amount, $hash) = explode('|', $decoded);

    // Volver a calcular el hash con la clave secreta
    $data = $discount_type . '|' . $discount_amount;
    $expected_hash = hash_hmac('sha256', $data, $secret_key);

    // Verificar que el hash calculado coincida con el hash almacenado
    if (hash_equals($expected_hash, $hash)) {
        // Retornar un array con el tipo y cantidad de descuento
        return array(
            'discount' => (float)$discount_amount,
            'type'     => $discount_type
        );
    }

    return false; // El código no es válido
}

//Elimina el prefijo prefijo_valor
function cod_remove_prefix($attribute) {
    if (strpos($attribute, '_') !== false) {
        $parts = explode('_', $attribute);
        return $parts[1];
    } else {
        return $attribute;
    }
}

/* Cifrar costo de envío */
function generate_shipping_code($shipping_method, $shipping_cost, $shipping_label) {
    $secret_key = D_S_K; // Tu clave secreta

    // Concatenar el método, costo y etiqueta del envío
    $data = $shipping_method . '|' . $shipping_cost . '|' . $shipping_label;

    // Crear un hash con HMAC usando la clave secreta
    $hash = hash_hmac('sha256', $data, $secret_key);

    // Cifrar los datos del envío y el hash en base64
    return base64_encode($shipping_method . '|' . $shipping_cost . '|' . $shipping_label . '|' . $hash);
}

/* Descifrar y validar costo de envío */
function validate_shipping_code($encoded_shipping_code) {
    $secret_key = D_S_K; // Tu clave secreta

    // Decodificar el código de envío cifrado
    $decoded = base64_decode($encoded_shipping_code);

    if (!$decoded) {
        return false; // El código no es válido
    }

    // Dividir los datos en partes (método, costo, etiqueta, hash)
    $parts = explode('|', $decoded);
    
    if (count($parts) !== 4) {
        return false; // Formato inválido
    }
    
    list($shipping_method, $shipping_cost, $shipping_label, $hash) = $parts;

    // Volver a calcular el hash con la clave secreta
    $data = $shipping_method . '|' . $shipping_cost . '|' . $shipping_label;
    $expected_hash = hash_hmac('sha256', $data, $secret_key);

    // Verificar que el hash calculado coincida con el hash almacenado
    if (hash_equals($expected_hash, $hash)) {
        // Retornar un array con los datos del envío
        return array(
            'method' => $shipping_method,
            'cost'   => (float)$shipping_cost,
            'label'  => $shipping_label
        );
    }

    return false; // El código no es válido
}