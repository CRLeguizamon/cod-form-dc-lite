<?php

/**
 * Cargador principal para funciones AJAX de WooCommerce del Plugin COD
 *
 * Este archivo actúa como punto de entrada para cargar todas las clases
 * modulares que manejan las diferentes funcionalidades AJAX del plugin.
 *
 * @package MODALCODF_Form_WC_DC
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Incluir todas las clases AJAX
require_once MODALCODF_DC_INC . 'woocommerce-extensions/ajax/class-cod-order-creator.php';
require_once MODALCODF_DC_INC . 'woocommerce-extensions/ajax/class-cod-cart-handler.php';
require_once MODALCODF_DC_INC . 'woocommerce-extensions/ajax/class-cod-shipping-handler.php';
require_once MODALCODF_DC_INC . 'woocommerce-extensions/ajax/class-cod-ajax-handler.php';

// Inicializar el manejador principal de AJAX
function cod_init_ajax_handlers() {
    new MODALCODF_Ajax_Handler();
}

// Hook para inicializar después de que WordPress esté completamente cargado
add_action('init', 'cod_init_ajax_handlers'); 