<?php 
/**
 * WooCommerce Additional Functionalities for Modal COD Form
 * 
 * Este archivo implementa las mejores prácticas de WordPress para cargar assets:
 * 
 * SCRIPTS:
 * - wp_enqueue_script() para cargar archivos JavaScript
 * - wp_localize_script() para datos AJAX y configuración
 * - Dependencias correctas entre scripts
 * - Carga en footer para mejor performance
 * 
 * ESTILOS:
 * - wp_enqueue_style() para cargar archivos CSS
 * - wp_add_inline_style() para CSS dinámico en lugar de <style> inline
 * - Versionado correcto para cache busting
 * 
 * ADMINISTRACIÓN:
 * - admin_enqueue_scripts hook para assets del admin
 * - Registro de estilos específicos para el admin
 * 
 * @package Modal_COD_Form
 * @since 1.0.0
 * @author crleguizamon
 */

// Includes
require_once(MODALCODF_DC_TEM . 'payment-button.php'); // Woocommerce Button Payment
require_once(MODALCODF_DC_INC . 'woocommerce-extensions/woo-ext-shortcodes.php'); // Shortcodes
require_once(MODALCODF_DC_INC . 'woocommerce-extensions/woo-ext-postypes.php'); // Extra Postypes
require_once(MODALCODF_DC_INC . 'woocommerce-extensions/woo-ext-ajax.php'); // AJAX


/**
 * Enqueue custom scripts for Modal COD Form
 * 
 * Carga los archivos JavaScript necesarios para el funcionamiento del plugin,
 * incluyendo utilidades globales, funcionalidad del formulario y creación de pedidos.
 * También configura los datos localizados para las solicitudes AJAX.
 * 
 * @since 1.0.0
 */
function enqueue_custom_scripts() {
    // Encola el archivo util.js que contiene funciones globales como decodeHtmlEntity y formatCurrency
    wp_enqueue_script('global-utils', MODALCODF_DC_ASSETS_URL . 'js/util.js', array('jquery'), MODALCODF_DC_VERSION, true);
    
    // Encola el script personalizado para la funcionalidad del formulario COD
    wp_enqueue_script('cod-form-custom', MODALCODF_DC_ASSETS_URL . 'js/cod-form-custom.js', array('jquery', 'global-utils'), MODALCODF_DC_VERSION, true);
    
    // Encola el script para crear pedidos en el proceso de compra
    wp_enqueue_script('cod-create-order', MODALCODF_DC_ASSETS_URL . 'js/cod-create-order.js', array('jquery', 'global-utils'), MODALCODF_DC_VERSION, true);
    
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

/**
 * Enqueue custom styles for Modal COD Form
 * 
 * Carga los archivos CSS necesarios para el estilo del modal y formulario.
 * También inyecta variables CSS dinámicas basadas en las configuraciones del usuario.
 * 
 * @since 1.0.0
 */
function enqueue_custom_styles() {
    // Encolar estilos principales del plugin
    wp_enqueue_style('cod-form-custom', MODALCODF_DC_ASSETS_URL . 'css/cod-form-custom.css', array(), MODALCODF_DC_VERSION);
    wp_enqueue_style('cod-form-style', MODALCODF_DC_ASSETS_URL . 'css/cod-form-style.css', array(), MODALCODF_DC_VERSION);
    
    // Agregar variables CSS dinámicas para el modal
    $highlight_color = get_option('cod_form_highlight_color', '#1d6740');
    $custom_css_vars = "
        :root {
            --highlight-color: {$highlight_color};
        }
    ";
    
    // Agregar CSS inline al estilo principal usando wp_add_inline_style()
    wp_add_inline_style('cod-form-style', $custom_css_vars);
}

/**
 * Ejemplo de cómo usar atributos async/defer con WordPress 6.3+
 * 
 * Esta función está comentada y sirve como referencia para implementaciones futuras
 * que requieran cargar scripts con atributos async o defer para mejorar la performance.
 * 
 * @since 1.0.0
 * @see https://make.wordpress.org/core/2023/07/14/registering-scripts-with-async-and-defer-attributes-in-wordpress-6-3/
 */
/*
function enqueue_scripts_with_attributes() {
    // Ejemplo de script con atributo defer (WordPress 6.3+)
    wp_enqueue_script(
        'cod-analytics-script',
        MODALCODF_DC_ASSETS_URL . 'js/analytics.js',
        array(),
        MODALCODF_DC_VERSION,
        array(
            'strategy'  => 'defer',  // o 'async'
            'in_footer' => true,
        )
    );
    
    // Ejemplo usando filtros para WordPress 5.7+ (método alternativo)
    add_filter('script_loader_tag', function($tag, $handle, $src) {
        if ('cod-analytics-script' === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }, 10, 3);
}
*/

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
    if (!MODALCODF_Product_Fields::is_cod_disabled($product_id)) {
        // Check if the template is variable.php
        if ($template_name == 'single-product/add-to-cart/variable.php') {
            // Override the template path to look in the plugin's templates folder
            $template = MODALCODF_DC_TEM . 'woocommerce/single-product/add-to-cart/variable.php';
        }
        
        // Check if the template is variation-add-to-cart-button.php
        if ($template_name == 'single-product/add-to-cart/variation-add-to-cart-button.php') {
            // Override the template path to look in the plugin's templates folder
            $template = MODALCODF_DC_TEM . 'woocommerce/single-product/add-to-cart/variation-add-to-cart-button.php';
        }
        
        // Check if the template is variation-add-to-cart-button.php
        if ($template_name == 'single-product/add-to-cart/simple.php') {
            // Override the template path to look in the plugin's templates folder
            $template = MODALCODF_DC_TEM . 'woocommerce/single-product/add-to-cart/simple.php';
        }
        // Check if the template is mini-cart.php
        if ($template_name == 'cart/mini-cart.php') {
            // Override the template path to look in the plugin's templates folder
            $template = MODALCODF_DC_TEM . 'woocommerce/cart/mini-cart.php';
        }
    }
    

    return $template;
}
