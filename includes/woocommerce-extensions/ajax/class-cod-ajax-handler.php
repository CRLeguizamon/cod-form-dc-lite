<?php

/**
 * Clase principal para manejar todos los endpoints AJAX del plugin COD
 *
 * @package MODALCODF_Form_WC_DC
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MODALCODF_Ajax_Handler {
    
    /**
     * Instancias de las clases manejadoras
     */
    private $order_creator;
    private $cart_handler;
    private $shipping_handler;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_handlers();
        $this->register_ajax_actions();
    }
    
    /**
     * Inicializar las clases manejadoras
     */
    private function init_handlers() {
        $this->order_creator = new MODALCODF_Order_Creator();
        $this->cart_handler = new MODALCODF_Cart_Handler();
        $this->shipping_handler = new MODALCODF_Shipping_Handler();
    }
    
    /**
     * Registrar todas las acciones AJAX
     */
    private function register_ajax_actions() {
        // Carrito
        add_action('wp_ajax_cod_form_add_to_cart', array($this->cart_handler, 'add_to_cart'));
        add_action('wp_ajax_nopriv_cod_form_add_to_cart', array($this->cart_handler, 'add_to_cart'));
        
        add_action('wp_ajax_remove_product_from_cart', array($this->cart_handler, 'remove_product'));
        add_action('wp_ajax_nopriv_remove_product_from_cart', array($this->cart_handler, 'remove_product'));
        
        // Modal
        add_action('wp_ajax_load_modal_content', array($this, 'load_modal_content'));
        add_action('wp_ajax_nopriv_load_modal_content', array($this, 'load_modal_content'));
        
        add_action('wp_ajax_cod_form_open_modal', array($this, 'open_modal'));
        add_action('wp_ajax_nopriv_cod_form_open_modal', array($this, 'open_modal'));
        
        // Órdenes
        add_action('wp_ajax_cod_create_order', array($this->order_creator, 'create_standard_order'));
        add_action('wp_ajax_nopriv_cod_create_order', array($this->order_creator, 'create_standard_order'));
        
        // Envío
        add_action('wp_ajax_get_shipping_methods', array($this->shipping_handler, 'get_methods'));
        add_action('wp_ajax_nopriv_get_shipping_methods', array($this->shipping_handler, 'get_methods'));
        
    }
    
    /**
     * Cargar contenido del modal
     */
    public function load_modal_content() {
        // Verificar nonce para seguridad
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'cod_load_modal_nonce')) {
            wp_die(esc_html__('Security check failed', 'modal-cod-form'));
        }
        
        // Recibe los datos enviados por AJAX
        $current_url = isset($_REQUEST['current_url']) ? sanitize_text_field(wp_unslash($_REQUEST['current_url'])) : '';
        $current_page_title = isset($_REQUEST['current_page_title']) ? sanitize_text_field(wp_unslash($_REQUEST['current_page_title'])) : '';
        $product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : '';

        // Hazlos disponibles para la plantilla
        set_query_var('cod_modal_current_url', $current_url);
        set_query_var('cod_modal_current_page_title', $current_page_title);
        set_query_var('cod_modal_product_id', $product_id);

        include(MODALCODF_DC_TEM . 'modal-content.php');
        wp_die();
    }
    
    /**
     * Abrir modal (placeholder para funcionalidad futura)
     */
    public function open_modal() {
        // Implementar lógica para abrir modal si es necesario
        wp_send_json_success(array('message' => 'Modal opened'));
    }
} 