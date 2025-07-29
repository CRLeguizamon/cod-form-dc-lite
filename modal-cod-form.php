<?php
/*
 * Plugin Name: Modal COD Form for woocommerce
 * Description: Con este plugin, el botón "Agregar al carrito" se transforma en un botón personalizable que abre una ventana modal con un formulario sencillo.
 * Version: 1.0
 * Author: crleguizamon
 * Author URI: https://mcodform.com/
 * Requires PHP: 7.4
 * Requires at least: 5.0
 * License: GPLv3
 */

// Protection against direct file access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Defines the constants needed for plugin operation.
 * 
 * @since 1.0
 * @return void
 */
function define_constants_cod() {
    if (!defined('MODALCODF_DC_THEME')) {
        define('MODALCODF_DC_THEME', get_template_directory());
    }
    if (!defined('MODALCODF_DC_VERSION')) {
        define('MODALCODF_DC_VERSION', '1.0.0');
    }
    if (!defined('MODALCODF_DC_DIR')) {
        define('MODALCODF_DC_DIR', plugin_dir_path(__FILE__));
    }
    if (!defined('MODALCODF_DC_INC')) {
        define('MODALCODF_DC_INC', MODALCODF_DC_DIR . 'includes/');
    }
    if (!defined('MODALCODF_DC_TEM')) {
        define('MODALCODF_DC_TEM', MODALCODF_DC_DIR . 'templates/');
    }
    if (!defined('MODALCODF_DC_ASSETS')) {
        define('MODALCODF_DC_ASSETS', MODALCODF_DC_DIR . 'assets/');
    }
    if (!defined('MODALCODF_DC_URL')) {
        define('MODALCODF_DC_URL', plugin_dir_url(__FILE__));
    }
    if (!defined('MODALCODF_DC_ASSETS_URL')) {
        define('MODALCODF_DC_ASSETS_URL', MODALCODF_DC_URL . 'assets/');
    }
    if (!defined('MODALCODF_UTILKEY')) {
        define('MODALCODF_UTILKEY', 'SjTY[/]Mij_HyCd0m5haF#m}m3]UXUTa');
    }
    
    // Constantes para textos promocionales PRO
    if (!defined('MODALCODF_PRO_TITLE')) {
        define('MODALCODF_PRO_TITLE', '🚀 ¡Desbloquea todo el potencial de COD Form!');
    }
    if (!defined('MODALCODF_PRO_SUBTITLE')) {
        define('MODALCODF_PRO_SUBTITLE', 'Actualiza a la versión PRO con 25% de descuento usando el cupón:');
    }
    if (!defined('MODALCODF_PRO_COUPON')) {
        define('MODALCODF_PRO_COUPON', 'PVWSRBYE');
    }
    if (!defined('MODALCODF_PRO_BUTTON_TEXT')) {
        define('MODALCODF_PRO_BUTTON_TEXT', '💎 Obtener COD Form PRO');
    }
    if (!defined('MODALCODF_PRO_GUARANTEE')) {
        define('MODALCODF_PRO_GUARANTEE', '✨ Garantía de devolución de dinero de 30 días • Soporte técnico incluido');
    }
    if (!defined('MODALCODF_PRO_URL_BASE')) {
        define('MODALCODF_PRO_URL_BASE', 'https://mcodform.com?utm_source=plugin-free');
    }
}
define_constants_cod();

/**
 * Checks if the PRO plugin is installed and active.
 * 
 * @since 1.0
 * @return boolean True if PRO plugin is active, false otherwise.
 */
function cod_check_pro_plugin() {
    $pro_plugin = 'cod-form-wc-dc/cod-form-wc-dc.php';

    if (is_plugin_active($pro_plugin)) {
        return true;
    }
}
        
if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

/**
 * Verifies if WooCommerce is installed and active.
 * Loads necessary files if dependencies are met.
 * 
 * @since 1.0
 * @return void
 */
function cod_check_woocommerce() {
    if(cod_check_pro_plugin()){
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(esc_html__('No se puede activar "Modal COD Form for WooCommerce" porque ya está activo "Modal COD Form - PRO". Por favor desactiva la versión PRO antes de activar la versión lite.', 'modal-cod-form'));
    
    }else{
        if (class_exists('WooCommerce')) {
            require_once(MODALCODF_DC_INC . 'admin/class-cod-admin-loader.php');
            require_once(MODALCODF_DC_INC . 'woocommerce-extensions.php');
            require_once(MODALCODF_DC_INC . 'util.php');

            // Inicializar el cargador administrativo
            MODALCODF_Admin_Loader::init();
        } else {
            add_action('admin_notices', 'cod_woocommerce_missing_notice');
        }
    }
}
add_action('plugins_loaded', 'cod_check_woocommerce');

/**
 * Displays a notice if WooCommerce is not active.
 * Informs the user that WooCommerce is required for the plugin to work.
 * 
 * @since 1.0
 * @return void
 */
function cod_woocommerce_missing_notice() {
    if (!class_exists('WooCommerce')) {
        ?>
        <div class="error notice">
            <p><?php esc_html_e('Para que funcione el plugin "Modal Cash On Delivery Checkout", necesitas instalar y activar WooCommerce.', 'modal-cod-form'); ?></p>
        </div>
        <?php
    }
}

/**
 * Adds action links to the plugins list.
 * 
 * @since 1.0
 * @param array $links Existing plugin action links
 * @return array Modified array of action links
 */
function cod_plugin_action_links($links) {
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=cod-offers') . '">' . esc_html__('Settings', 'modal-cod-form') . '</a>',
    );

    return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cod_plugin_action_links');