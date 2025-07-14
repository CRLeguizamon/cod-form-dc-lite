<?php
/**
 * Admin Loader Class
 *
 * @package     Modal_CODL_Form
 * @since       4.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Admin_Loader {
    /**
     * Inicializa el cargador de la parte administrativa
     */
    public static function init() {
        // Cargar archivos de configuración
        require_once(CODL_DC_INC . 'admin/settings/abstract-class-cod-settings.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-general.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-design.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-menu-icon.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-payment-button.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-shortcodes.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-pro-features.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-manager.php');
        require_once(CODL_DC_INC . 'admin/settings/class-cod-settings-text-customization.php');
        require_once(CODL_DC_INC . 'admin/class-cod-product-fields.php');

        // Inicializar el gestor de configuraciones
        CODL_Settings_Manager::get_instance();

        // Inicializar los campos de producto
        new CODL_Product_Fields();
    }
} 