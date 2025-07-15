<?php
/**
 * Pro Features Settings Class
 *
 * @package     Modal_CODL_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class CODL_Settings_Pro_Features extends CODL_Settings_Abstract {
    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'pro_features_main';
        $this->title = esc_html__('🚀 Pro Features', 'cod-form-dc-lite');
        parent::__construct();
    }

    /**
     * Inicializa la sección
     */
    protected function init() {
        add_action('woocommerce_admin_field_cod_pro_features_section', array($this, 'render_pro_features_section'));
        add_action('woocommerce_admin_field_cod_pro_upgrade_notice', array($this, 'render_upgrade_notice'));
    }

    /**
     * Obtiene las configuraciones
     */
    public function get_settings() {
        return array(
            array(
                'id'   => 'cod_pro_upgrade_notice',
                'type' => 'cod_pro_upgrade_notice'
            ),
            array(
                'id'   => 'cod_advanced_features',
                'type' => 'cod_pro_features_section'
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'cod_form_pro_features_section_end'
            )
        );
    }

    /**
     * Renderiza la notificación de actualización
     */
    public function render_upgrade_notice($field) {
        echo '<tr><td colspan="2">';
        echo '<div style="border: 1px solid #ddd; background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">';
        echo '<h3 style="margin: 0 0 10px 0; color: #101538;">🚀 Versión PRO Disponible</h3>';
        echo '<p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">Desbloquea características adicionales como ofertas de cantidad, downsells, webhooks y más.</p>';
        echo '<a href="' . esc_url(CODL_PRO_URL_BASE . '&utm_medium=pro-features') . '" target="_blank" class="button button-secondary" style="text-decoration: none;">Ver Características PRO</a>';
        echo '</div>';
        echo '</td></tr>';
    }

    /**
     * Renderiza las secciones de características PRO
     */
    public function render_pro_features_section($field) {
        echo '<tr><td colspan="2">';
        
        // Características de conversión
        $this->render_conversion_features();
        
        echo '</td></tr>';
    }

    /**
     * Renderiza las características de conversión
     */
    private function render_conversion_features() {
        ?>
        <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
            <h3 style="margin: 0 0 15px 0; color: #101538; font-size: 18px;">Características Adicionales en la Versión PRO</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Formulario en un solo paso</strong></span>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Botón junto al "Añadir al carrito"</strong></span>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Ofertas de cantidad</strong></span>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Downsells</strong></span>
                        </li>
                        <li style="padding: 8px 0; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Webhook para automatizaciones</strong></span>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Ciudades y departamentos automáticos</strong></span>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Redirección personalizada</strong></span>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Botón secundario personalizado</strong></span>
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #dee2e6; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Compatible con Dropi y Effy</strong></span>
                        </li>
                        <li style="padding: 8px 0; display: flex; align-items: center;">
                            <span style="color: #28a745; font-weight: bold; margin-right: 10px;">✓</span>
                            <span><strong>Shortcodes adicionales</strong></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div style="margin-top: 15px; text-align: center;">
                <p style="margin: 0; color: #666; font-size: 14px;">
                    <a href="https://demo-mcod.devcristian.com/" target="_blank" style="color: #667eea; text-decoration: none;">Ver demostración</a>
                </p>
            </div>
        </div>
        <?php
    }
} 