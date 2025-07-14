<?php

/**
 * Clase para manejar ubicaciones (estados, ciudades, distritos) COD
 *
 * @package CODL_Form_WC_DC
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Location_Handler {
    
    /**
     * Obtener estados por país
     */
    public function get_states_by_country() {
        $country = sanitize_text_field($_POST['country']);
        $states = WC()->countries->get_states($country);
        if (!empty($states)) {
            foreach ($states as $state_code => $state_name) {
                echo '<option value="' . esc_attr($state_code) . '">' . esc_html($state_name) . '</option>';
            }
        } else {
            echo '<option value="">' . esc_html__('No states found', 'cod-form-dc-lite') . '</option>';
        }

        wp_die();
    }
} 