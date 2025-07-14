<?php 
/**
 * Woocommerce Aditional Funtionalities
 */

// Includes
require_once(CODL_DC_TEM . 'payment-button.php'); // Woocommerce Button Payment
require_once(CODL_DC_INC . 'woocommerce-extensions/woo-ext-shortcodes.php'); // Shortcodes
require_once(CODL_DC_INC . 'woocommerce-extensions/woo-ext-postypes.php'); // Extra Postypes
require_once(CODL_DC_INC . 'woocommerce-extensions/woo-ext-ajax.php'); // AJAX


/**
 *  Enqueue custom scripts
 */
function enqueue_custom_scripts() {
    // Encola el archivo util.js que contiene funciones globales como decodeHtmlEntity y formatCurrency
    wp_enqueue_script('global-utils', CODL_DC_ASSETS_URL . 'js/util.js', array('jquery'), CODL_DC_VERSION, true);
    
    // Encola el script personalizado para la funcionalidad del formulario COD
    wp_enqueue_script('cod-form-custom', CODL_DC_ASSETS_URL . 'js/cod-form-custom.js', array('jquery', 'global-utils'), CODL_DC_VERSION, true);
    
    // Encola el script para crear pedidos en el proceso de compra
    wp_enqueue_script('cod-create-order', CODL_DC_ASSETS_URL . 'js/cod-create-order.js', array('jquery', 'global-utils'), CODL_DC_VERSION, true);
    
    // Configura los datos localizados para el script cod-form-custom, proporcionando la URL de admin-ajax.php
    wp_localize_script('cod-form-custom', 'cod_form_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('cod_shipping_methods_nonce'),
        'add_to_cart_nonce' => wp_create_nonce('cod_add_to_cart_nonce'),
        'remove_product_nonce' => wp_create_nonce('cod_remove_product_nonce'),
        'load_modal_nonce' => wp_create_nonce('cod_load_modal_nonce'),
        'create_order_nonce' => wp_create_nonce('cod_form_nonce'),
        'number_of_decimals' => get_option('woocommerce_price_num_decimals', 2),
        'decimal_separator' => wc_get_price_decimal_separator(),
        'thousand_separator' => wc_get_price_thousand_separator(),
        'currency_symbol' => get_woocommerce_currency_symbol()
    ));
}
// Enqueue custom styles for the modal
function enqueue_custom_styles() {
    if (true) {
        wp_enqueue_style('cod-form-custom', CODL_DC_ASSETS_URL . 'css/cod-form-custom.css', array(), CODL_DC_VERSION);
    }
    if (true) {
        wp_enqueue_style('cod-form-style', CODL_DC_ASSETS_URL . 'css/cod-form-style.css', array(), CODL_DC_VERSION);
    }
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles');
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Reemplazar plantillas de woocommerce
add_filter('woocommerce_locate_template', 'cod_form_wc_dc_locate_template', 10, 3);


function cod_form_wc_dc_locate_template($template, $template_name, $template_path) {
    global $woocommerce;
    
    // Obtener el ID del producto actual de forma segura
    $product_id = null;
    if (is_product()) {
        $product_id = get_the_ID(); 
    }
    
    // Si no estamos en una página de producto o el botón no está deshabilitado
    if (!CODL_Product_Fields::is_cod_disabled($product_id)) {
        // Check if the template is variable.php
        if ($template_name == 'single-product/add-to-cart/variable.php') {
            // Override the template path to look in the plugin's templates folder
            $template = CODL_DC_TEM . 'woocommerce/single-product/add-to-cart/variable.php';
        }
        
        // Check if the template is variation-add-to-cart-button.php
        if ($template_name == 'single-product/add-to-cart/variation-add-to-cart-button.php') {
            // Override the template path to look in the plugin's templates folder
            $template = CODL_DC_TEM . 'woocommerce/single-product/add-to-cart/variation-add-to-cart-button.php';
        }
        
        // Check if the template is variation-add-to-cart-button.php
        if ($template_name == 'single-product/add-to-cart/simple.php') {
            // Override the template path to look in the plugin's templates folder
            $template = CODL_DC_TEM . 'woocommerce/single-product/add-to-cart/simple.php';
        }
        // Check if the template is mini-cart.php
        if ($template_name == 'cart/mini-cart.php') {
            // Override the template path to look in the plugin's templates folder
            $template = CODL_DC_TEM . 'woocommerce/cart/mini-cart.php';
        }
    }
    

    return $template;
}
