<?php
/**
 * WooCommerce Modal Form Content
 *
 * @package     Modal_CODL_Form
 * @author      Cristian Leguizamón - Devcristian
 * @version     1.0
 * 
 * Este template genera el contenido del modal de checkout incluyendo detalles del carrito,
 * opciones de envío, formulario de información del cliente y botón de pago.
 */

// ==============================================
// Configuración inicial y variables globales
// ==============================================
$cart_items = WC()->cart->get_cart();
$subtotal = WC()->cart->subtotal;
$total = WC()->cart->total;
$currency_symbol = get_woocommerce_currency_symbol();

// ==============================================
// Configuración de ubicación y países
// ==============================================
$country_selection = get_option('cod_form_country_selection', 'base_country');
$countries = WC()->countries->get_countries();
$country_code = ($country_selection === 'base_country') ? WC()->countries->get_base_country() : key($countries);

// Configuración de estados usando WooCommerce nativo
$states = WC()->countries->get_states($country_code);

// ==============================================
// Configuración del formulario
// ==============================================
$modal_title = get_option('cod_form_modal_title', esc_html__('Pago contra entrega', 'cod-form-dc-lite'));
$modal_title_size = get_option('cod_form_modal_title_size', esc_html__('28', 'cod-form-dc-lite'));
$highlight_color = get_option('cod_form_highlight_color', '#1d6740');
$disabled_fields = get_option('cod_form_disable_form_fields', array());
$is_email_mandatory = get_option('cod_form_email_mandatory', 'no');

// Configuración de layout
$horizontal_layout = get_option('cod_form_horizontal_layout', 'no') === 'yes';
$disable_labels = get_option('cod_form_disable_labels', 'no') === 'yes' ? 'cod_hide' : '';
$form_group_class = ($horizontal_layout && !$disable_labels) ? 'form-group cod-form-group-row' : 'form-group';

// ==============================================
// Configuración de envío
// ==============================================
$ignore_wc_shipping = get_option('cod_form_ignore_wc_shipping', 'no');
$select_state = $ignore_wc_shipping == 'no' ? 'select_state' : 'free_shipping';
$shipping_table = $ignore_wc_shipping == 'no' ? wc_price(0) : esc_html__('Gratis', 'cod-form-dc-lite');

// ==============================================
// Configuración de botones y estilos
// ==============================================
$background_color = get_option('cod_form_background_color', '#000000');
$text_color = get_option('cod_form_text_color', '#ffffff');
$text_size = get_option('cod_form_text_size', '1');
$border_radius = get_option('cod_form_border_radius', '0');
$border_width = get_option('cod_form_border_width', '0');
$border_color = get_option('cod_form_border_color', '#000000');
$box_shadow = get_option('cod_form_box_shadow', '0');
$button_animation = get_option('cod_form_button_animation', 'none');
$button_width = get_option('cod_form_button_width', 'full');

// ==============================================
// Configuración de botón secundario
// ===========================================z===
$enable_wc_checkout = get_option('cod_form_enable_wc_checkout', 'no');
$page_wc_checkout = get_option('cod_form_select_checkout_page', '');
$wc_checkout_text = get_option('cod_form_checkout_link_text', '¡Quiero pagar ya!');

$secundary_background_color = get_option('cod_form_secundary_button_background_color', '#000000');
$secundary_text_color = get_option('cod_form_secundary_button_text_color', '#ffffff');
$secundary_text_size = get_option('cod_form_secundary_button_text_size', '1');
$secundary_button_width = get_option('cod_form_secundary_button_button_width', 'normal');
$secundary_border_radius = get_option('cod_form_secundary_button_border_radius', '0');
$secundary_border_width = get_option('cod_form_secundary_button_border_width', '0');
$secundary_border_color = get_option('cod_form_secundary_butto_border_color', '#000000');
$secundary_box_shadow = get_option('cod_form_secundary_button_box_shadow', '0');
$secundary_button_animation = get_option('cod_form_secundary_button_animation', 'none');

// ==============================================
// Configuración de imágenes y banners
// ==============================================
$image_banner_top = get_option('cod_form_image_url_top', '');
$image_banner_bottom = get_option('cod_form_image_url_bottom', '');

// ==============================================
// Configuración de textos del formulario
// ==============================================
$first_name_label = get_option('cod_form_first_name_label', 'Nombre');
$first_name_placeholder = get_option('cod_form_first_name_placeholder', 'Nombre');

$last_name_label = get_option('cod_form_last_name_label', 'Apellido');
$last_name_placeholder = get_option('cod_form_last_name_placeholder', 'Apellido');

$phone_label = get_option('cod_form_phone_label', 'Teléfono');
$phone_placeholder = get_option('cod_form_phone_placeholder', 'Teléfono');

$state_label = get_option('cod_form_state_label', 'Departamento');
$state_placeholder = get_option('cod_form_state_placeholder', 'Departamento');

$city_label = get_option('cod_form_city_label', 'Ciudad');
$city_placeholder = get_option('cod_form_city_placeholder', 'Ciudad');

$district_label = get_option('cod_form_district_label', 'Distrito');
$district_placeholder = get_option('cod_form_district_placeholder', 'Distrito');

$address_label = get_option('cod_form_address_label', 'Dirección');
$address_placeholder = get_option('cod_form_address_placeholder', 'Dirección');

$address_2_label = get_option('cod_form_address_2_label', 'Dirección 2');
$address_2_placeholder = get_option('cod_form_address_2_placeholder', 'Dirección 2');

$email_label = get_option('cod_form_email_label', 'Correo electrónico');
$email_placeholder = get_option('cod_form_email_placeholder', 'Correo electrónico');

$order_notes_label = get_option('cod_form_order_notes_label', 'Notas de la orden');
$order_notes_placeholder = get_option('cod_form_order_notes_placeholder', 'Notas de la orden');

$button_text = get_option('cod_form_button_payment_text', 'Completar orden');

// ==============================================
// Configuración de términos y condiciones
// ==============================================
$terms_page_id = get_option('cod_form_terms_checkbox_link');
if (!empty($terms_page_id)) {
    $terms_link = get_permalink($terms_page_id);
    $terms_link_html = '<a href="' . esc_url($terms_link) . '" target="_blank">' . get_option('cod_form_terms_checkbox_link_text', esc_html__('términos y condiciones', 'cod-form-dc-lite')) . '</a>';
} else {
    $terms_link_html = '';
}

// ==============================================
// Funciones auxiliares
// ==============================================
/**
 * Verifica si un campo está deshabilitado en la configuración del formulario
 *
 * @param string $field          Nombre del campo a verificar
 * @param array  $disabled_fields Array de campos deshabilitados desde la configuración
 * @return bool  True si el campo está deshabilitado, false en caso contrario
 */
function is_field_disabled($field, $disabled_fields) {
    return in_array($field, $disabled_fields);
}

// ==============================================
// Inicio del template
// ==============================================
?>

<style>
    :root {
        --highlight-color: <?php echo esc_attr($highlight_color); ?>;
    }
</style>

<span class="cod-modal-close button_modal_close">
    <img width="15" src="<?php echo esc_url(CODL_DC_ASSETS_URL . 'img/cod_x_black.png'); ?>" alt="<?php esc_attr_e('Cerrar modal', 'cod-form-dc-lite'); ?>">
</span>
<?php if (empty($cart_items)) : ?>
    <div class="not_cart_items">
        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" fill="none">
            <path d="M9 17C9.85038 16.3697 10.8846 16 12 16C13.1154 16 14.1496 16.3697 15 17" stroke="#000" stroke-width="1.5" stroke-linecap="round"/>
            <ellipse cx="15" cy="10.5" rx="1" ry="1.5" fill="#000"/>
            <ellipse cx="9" cy="10.5" rx="1" ry="1.5" fill="#000"/>
            <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="#000" stroke-width="1.5" stroke-linecap="round"/>
        </svg>    
        
        <h4><?php echo esc_html__('No hay productos en tu carrito de compra.', 'cod-form-dc-lite'); ?></h4>
    </div>
<?php else : ?>
<?php 
    if (!empty($image_banner_top)) {
        echo '<img src="' . esc_url($image_banner_top) . '" alt="' . esc_attr__('Banner superior', 'cod-form-dc-lite') . '" style="max-width:100%;">';
    }
?>

<h3 style="font-size:<?php echo esc_attr($modal_title_size); ?>px;"><?php echo esc_html($modal_title); ?></h3>

<table class="cod-woocommerce-cart-table">
    <tbody>
        <?php foreach ($cart_items as $cart_item_key => $cart_item) : ?>
            <?php $product = $cart_item['data']; ?>
            <tr class="cod-cart woocommerce-cart-form__cart-item cod_cart_item" data="<?php echo esc_attr($product->get_id()); ?>">
                <td class="cod-product-img">
                    <?php echo wp_kses_post($product->get_image('thumbnail')); ?>
                    <?php echo '<span class="cod-cart-quantity">' . esc_html($cart_item['quantity']) . '</span>'; ?>
                </td>
                <td class="cod-product-name">
                    <?php echo esc_html($product->get_name()); ?>
                </td>
                <td class="cod-product-subtotal">
                    <?php echo wp_kses_post(WC()->cart->get_product_subtotal($product, $cart_item['quantity'])); ?>
                </td>
                <td class="cod-product-remove">
                    <button type="button" class="remove-product" data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>">
                        <img width="15" src="<?php echo esc_url(CODL_DC_ASSETS_URL . 'img/cod_x_red.png'); ?>" alt="<?php esc_attr_e('Eliminar producto', 'cod-form-dc-lite'); ?>">
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="cod_table subtotal-total">
    <div class="cod_td subtotal_td">
        <span><?php echo esc_html__('Subtotal', 'cod-form-dc-lite'); ?>:</span>
        <span class="woocommerce-Price-amount amount subtotal-amount">
            <?php echo wp_kses_post(wc_price($subtotal)); ?>
        </span>
    </div>
    <div class="cod_td">
        <span><?php echo esc_html__('Envío', 'cod-form-dc-lite'); ?>:</span>
        <span class="woocommerce-Price-amount amount">
            <span class="shipping-amount"><?php echo wp_kses_post($shipping_table); ?></span>
        </span>
    </div>
    <div class="cod_td total">
        <span><?php echo esc_html__('Total', 'cod-form-dc-lite'); ?>:</span>
        <span class="woocommerce-Price-amount amount total-amount">
            <?php echo wp_kses_post(wc_price($total)); ?>
        </span>
    </div>
</div>

<form id="checkout_form" action="#" method="post" data-currency="<?php echo esc_attr($currency_symbol); ?>">
    <?php wp_nonce_field('cod_form_nonce', '_wpnonce'); ?>
    <?php if ($country_selection === 'enable_select') : ?>
        <div class="<?php echo esc_attr($form_group_class); ?>">
            <label for="country" ><?php echo esc_html__('País', 'cod-form-dc-lite'); ?><div class="cod_required_input">*</div></label>
            <select style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/location.svg'); ?>);" class="form-control" id="country" name="country" required>
                <option value=""><?php echo esc_html__('Seleccionar país', 'cod-form-dc-lite'); ?></option>
                <?php foreach ($countries as $code => $name) : ?>
                    <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>
    
    <div class="<?php echo esc_attr($form_group_class); ?>">
        <label for="first_name" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($first_name_label); ?><div class="cod_required_input">*</div></label>
        <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/user.svg'); ?>);" type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php echo esc_attr($first_name_placeholder); ?>" required>
    </div>
    <div class="<?php echo esc_attr($form_group_class); ?>">
        <label for="last_name" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($last_name_label); ?><div class="cod_required_input">*</div></label>
        <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/user.svg'); ?>);" type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php echo esc_attr($last_name_placeholder); ?>" required>
    </div>
    <div class="<?php echo esc_attr($form_group_class); ?>">
        <label for="phone" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($phone_label); ?><div class="cod_required_input">*</div></label>
        <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/phone.svg'); ?>);" type="tel" class="form-control" id="phone" name="phone" placeholder="<?php echo esc_attr($phone_placeholder); ?>" required>
    </div>
    <div class="<?php echo esc_attr($form_group_class); ?>">
        <label for="state" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($state_label); ?><div class="cod_required_input">*</div></label>
        <select style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/location.svg'); ?>);" class="form-control <?php echo esc_attr($select_state); ?>" id="state" name="state" required>
            <option value=""><?php echo esc_html($state_placeholder); ?></option>
            <?php foreach ($states as $state_code => $state_name) : ?>
                <option value="<?php echo esc_attr($state_code); ?>"><?php echo esc_html($state_name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php if ($ignore_wc_shipping == 'no'): ?>
    <div id="shipping-methods"></div>
    <?php endif; ?>
    
    <div class="<?php echo esc_attr($form_group_class); ?>">
        <label for="city" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($city_label); ?><div class="cod_required_input">*</div></label>
        <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/location.svg'); ?>);" type="text" class="form-control" id="city" name="city" placeholder="<?php echo esc_attr($city_placeholder); ?>" required>
    </div>

    <div class="<?php echo esc_attr($form_group_class); ?>">
        <label for="address" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($address_label); ?><div class="cod_required_input">*</div></label>
        <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/location.svg'); ?>);" type="text" class="form-control" id="address" name="address" placeholder="<?php echo esc_attr($address_placeholder); ?>" required>
    </div>
    <?php if (!is_field_disabled('address_2', $disabled_fields)): ?>
        <div class="<?php echo esc_attr($form_group_class); ?>">
            <label for="address_2" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($address_2_label); ?><div class="cod_required_input">*</div></label>
            <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/location.svg'); ?>);" type="text" class="form-control" id="address_2" name="address_2" placeholder="<?php echo esc_attr($address_2_placeholder); ?>" required>
        </div>
    <?php endif; ?>
    
    <?php if (!is_field_disabled('email', $disabled_fields)): ?>
        <div class="<?php echo esc_attr($form_group_class); ?>">
            <label for="email" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($email_label); ?><?php if($is_email_mandatory == 'yes'): echo '<div class="cod_required_input">*</div>';endif;?></label>
            <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/email.svg'); ?>);" type="email" class="form-control" id="email" name="email" <?php if($is_email_mandatory == 'no'): echo 'placeholder="' . esc_attr__('Opcional', 'cod-form-dc-lite') . '"'; else: echo 'placeholder="' . esc_attr($email_placeholder) . '" required';endif; ?> >
        </div>
    <?php endif; ?>
    
    <?php if (!is_field_disabled('order_comments', $disabled_fields)) : ?>
        <div class="<?php echo esc_attr($form_group_class); ?> cod_notes">
            <label for="order_comments" class="<?php echo esc_attr($disable_labels); ?>"><?php echo esc_html($order_notes_label); ?></label>
            <input style="background-image:url(<?php echo esc_url(CODL_DC_ASSETS_URL.'img/msj.svg'); ?>);" type="text" class="form-control" id="order_comments" name="order_comments" placeholder="<?php echo esc_attr($order_notes_placeholder); ?>" >
        </div>
    <?php endif; ?>
    
    <?php if (!is_field_disabled('terms', $disabled_fields)) : ?>
        <div class="form-group cod_terms">
            <label for="terms_checkbox">
                <input type="checkbox" id="terms_checkbox" name="terms_checkbox" required>
                <?php echo esc_html(get_option('cod_form_terms_checkbox_text', esc_html__('I agree to the terms and conditions', 'cod-form-dc-lite'))) . ' ' . wp_kses_post($terms_link_html); ?>
            </label>
        </div>
    <?php endif; ?>
    
    <button type="submit" class="btn cod-modal-button btn-primary <?php echo esc_attr($button_animation); ?>" style="
        background-color: <?php echo esc_attr($background_color); ?>;
        color: <?php echo esc_attr($text_color); ?>;
        border-radius: <?php echo esc_attr($border_radius); ?>px;
        border-width: <?php echo esc_attr($border_width); ?>px;
        border-color: <?php echo esc_attr($border_color); ?>;
        border-style: solid;
        padding: 10px 25px;
        box-shadow: 0 0 <?php echo esc_attr($box_shadow); ?>rem <?php echo esc_attr($box_shadow); ?>rem rgba(0, 0, 0, 0.1);
        font-size: <?php echo esc_attr($text_size); ?>rem;
        width: 100%;
        cursor: pointer;
    ">
        <?php echo esc_html($button_text); ?>
    </button>
    
    
    <?php if (!empty($image_banner_bottom)) {
        echo '<img src="' . esc_url($image_banner_bottom) . '" alt="' . esc_attr__('Footer', 'cod-form-dc-lite') . '" style="max-width:100%;">';
    } ?>
    
</form>
<?php endif; ?>