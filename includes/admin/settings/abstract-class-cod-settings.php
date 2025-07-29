<?php
/**
 * Abstract Settings Class
 *
 * @package     Modal_COD_Form
 * @since       1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

abstract class MODALCODF_Settings_Abstract {
    /**
     * @var string ID único de la sección
     */
    protected $id;

    /**
     * @var string Título de la sección
     */
    protected $title;

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Inicializa la sección
     */
    abstract protected function init();

    /**
     * Obtiene las configuraciones de la sección
     */
    abstract public function get_settings();

    /**
     * Obtiene el ID de la sección
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Obtiene el título de la sección
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Muestra las configuraciones
     */
    public function display() {
        $settings = $this->get_settings();
        foreach ($settings as $field) {
            if (isset($field['type']) && $field['type'] === 'media_upload') {
                $this->render_custom_field($field);
            } else {
                woocommerce_admin_fields(array($field));
            }
        }
    }

    /**
     * Guarda las configuraciones
     */
    public function save() {
        woocommerce_update_options($this->get_settings());
    }

    /**
     * Renderiza un campo personalizado
     */
    public function render_custom_field($field) {
        switch ($field['type']) {
            case 'media_upload':
                $this->render_media_upload_field($field);
                break;
            default:
                break;
        }
    }

    /**
     * Renderiza un campo de subida de medios
     */
    private function render_media_upload_field($field) {
        $value = get_option($field['id'], $field['default']);
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($field['id']); ?>"><?php echo esc_html($field['title']); ?></label>
                <?php if (isset($field['desc_tip']) && $field['desc_tip']): ?>
                    <span class="woocommerce-help-tip" data-tip="<?php echo esc_attr($field['desc']); ?>"></span>
                <?php endif; ?>
            </th>
            <td class="forminp forminp-<?php echo esc_attr($field['type']); ?>">
                <input
                    type="text"
                    name="<?php echo esc_attr($field['id']); ?>"
                    id="<?php echo esc_attr($field['id']); ?>"
                    value="<?php echo esc_attr($value); ?>"
                    class="<?php echo esc_attr($field['class'] ?? ''); ?>"
                    style="<?php echo esc_attr($field['css'] ?? ''); ?>"
                />
                <button type="button" class="button button-secondary media-upload-button"><?php esc_html_e('Seleccionar Imagen', 'modal-cod-form'); ?></button>
                <div class="media-preview">
                    <?php if ($value): ?>
                        <img src="<?php echo esc_url($value); ?>" style="max-width:100px;height:auto;" />
                    <?php endif; ?>
                </div>
                <?php if (!isset($field['desc_tip']) || !$field['desc_tip']): ?>
                    <p class="description"><?php echo wp_kses_post($field['desc']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
} 