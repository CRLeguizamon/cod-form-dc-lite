<?php
/**
 * Settings Manager Class
 *
 * @package     Modal_MODALCODF_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MODALCODF_Settings_Manager {
    /**
     * @var MODALCODF_Settings_Manager
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $settings = array();

    /**
     * Constructor privado para Singleton
     */
    private function __construct() {
        $this->init_settings();
        $this->init_hooks();
    }

    /**
     * Obtiene la instancia única
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inicializa las configuraciones
     */
    private function init_settings() {
        $this->settings = array(
            new MODALCODF_Settings_General(),
            new MODALCODF_Settings_Design(),
            new MODALCODF_Settings_Payment_Button(),
            new MODALCODF_Settings_Text_Customization(),
            new MODALCODF_Settings_Menu_Icon(),
            new MODALCODF_Settings_Shortcodes(),
            new MODALCODF_Settings_Pro_Features()
        );
    }

    /**
     * Inicializa los hooks
     */
    private function init_hooks() {
        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
        add_action('woocommerce_sections_cod_form', array($this, 'display_sections'));
        add_action('woocommerce_settings_cod_form', array($this, 'display_settings'));
        add_action('woocommerce_update_options_cod_form', array($this, 'save_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    /**
     * Verifica si estamos en la página de configuración correcta
     */
    private function is_settings_page($hook) {
        // Verificar que estemos en la página de configuración de WooCommerce
        if ('woocommerce_page_wc-settings' !== $hook) {
            return false;
        }

        // Verificar que estemos en la pestaña correcta
        if (!isset($_GET['tab']) || 'cod_form' !== sanitize_text_field(wp_unslash($_GET['tab']))) {
            return false;
        }

        // Si estamos guardando configuraciones, verificar el nonce
        if (isset($_REQUEST['save']) || isset($_REQUEST['action'])) {
            if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'woocommerce-settings')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtiene la sección actual de forma segura
     */
    private function get_current_section() {
        // Verificar que estemos en la página correcta y el nonce sea válido
        if (!$this->is_settings_page('woocommerce_page_wc-settings')) {
            return 'general';
        }

        // Obtener y validar la sección
        $allowed_sections = array_map(function($setting) {
            return $setting->get_id();
        }, $this->settings);

        $section = isset($_GET['section']) ? sanitize_text_field(wp_unslash($_GET['section'])) : 'general';
        
        // Asegurarse de que la sección sea válida
        return in_array($section, $allowed_sections, true) ? $section : 'general';
    }

    /**
     * Carga los estilos CSS en la administración
     */
    public function enqueue_admin_styles($hook) {
        // Verificar si estamos en la página correcta
        if (!$this->is_settings_page($hook)) {
            return;
        }

        // Registrar y cargar el CSS
        wp_register_style(
            'cod-form-admin-styles',
            MODALCODF_DC_URL . 'assets/css/admin-settings.css',
            array(),
            MODALCODF_DC_VERSION
        );
        wp_enqueue_style('cod-form-admin-styles');

        // Registrar y cargar el JavaScript
        wp_enqueue_media();
        wp_register_script(
            'cod-form-admin-scripts',
            MODALCODF_DC_URL . 'assets/js/admin-settings.js',
            array('jquery', 'media-upload'),
            MODALCODF_DC_VERSION,
            true
        );
        wp_enqueue_script('cod-form-admin-scripts');

        // Localizar script con nonce para operaciones AJAX
        wp_localize_script('cod-form-admin-scripts', 'codFormAdmin', array(
            'nonce' => wp_create_nonce('cod_form_admin_nonce')
        ));
    }

    /**
     * Agrega la pestaña de configuración
     */
    public function add_settings_tab($settings_tabs) {
        $settings_tabs['cod_form'] = esc_html__('Modal COD Form', 'modal-cod-form');
        return $settings_tabs;
    }

    /**
     * Muestra las secciones de configuración
     */
    public function display_sections() {
        $sections = array();
        foreach ($this->settings as $setting) {
            $sections[$setting->get_id()] = $setting->get_title();
        }

        if (empty($sections)) {
            return;
        }

        $current_section = $this->get_current_section();

        echo '<div class="cod-form-settings">';
        echo '<ul class="subsubsub">';
        foreach ($sections as $id => $label) {
            $url = add_query_arg(
                array(
                    'page' => 'wc-settings',
                    'tab' => 'cod_form',
                    'section' => sanitize_title($id),
                    '_wpnonce' => wp_create_nonce('woocommerce-settings'),
                ),
                admin_url('admin.php')
            );
            
            echo '<li>';
            echo '<a class="' . esc_attr($current_section == $id ? 'current' : '') . '" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
            echo '</li>';
        }
        echo '</ul><br class="clear" />';
        echo '</div>';
    }

    /**
     * Muestra las configuraciones
     */
    public function display_settings() {
        $current_section = $this->get_current_section();

        echo '<div class="cod-form-settings">';
        foreach ($this->settings as $setting) {
            if ($setting->get_id() === $current_section) {
                $setting->display();
                break;
            }
        }
        echo '</div>';
    }

    /**
     * Guarda las configuraciones
     */
    public function save_settings() {
        $current_section = $this->get_current_section();

        foreach ($this->settings as $setting) {
            if ($setting->get_id() === $current_section) {
                $setting->save();
                break;
            }
        }
    }

    /**
     * Obtiene una configuración específica
     */
    public function get_setting($id) {
        foreach ($this->settings as $setting) {
            if ($setting->get_id() === $id) {
                return $setting;
            }
        }
        return null;
    }

    /**
     * Obtiene todas las configuraciones
     */
    public function get_settings() {
        return $this->settings;
    }
} 