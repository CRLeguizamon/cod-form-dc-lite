<?php

/**
 * Remove default "Add to Cart" button and replace it with a custom button.
 */
function remove_woocommerce_loop_add_to_cart_buttons() {
    // Solo eliminar el botón si no está habilitada la opción de mostrar ambos botones
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
}
add_action('wp', 'remove_woocommerce_loop_add_to_cart_buttons');

/**
 * Display custom button with text, background color, and text color specified in plugin settings.
 */
function custom_single_product_button($product) {
    // Verifica que el producto sea un objeto y que WooCommerce esté disponible
    if (!is_a($product, 'WC_Product') || !class_exists('WooCommerce')) {
        return; // Salir si no es un producto válido o WooCommerce no está disponible
    }

    // Inicia el buffer de salida
    ob_start();

    // Get the setting for disabling quantity selection
    $disable_quantity_selection = get_option('cod_form_disable_quantity_selection', 'no');
    
    // Get button sticky
    $cod_form_button_sticky = get_option('cod_form_button_sticky', 'no') === 'yes' ? 'cod_container_button_order cod_sticky' : 'cod_container_button_order';

    // Check if the product is in the cart
    $product_id = $product->get_id();
    if(is_null($product_id)){
        return "El producto no se reconoce. (ID Inválido)";
    }
    if ( isset($_SERVER['REQUEST_URI']) && strpos(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), 'elementor') !== false ) {
        return "La vista previa no está disponible en el editor de Elementor";
    }
    $product_cart_id = WC()->cart->generate_cart_id($product->get_id());
    $is_in_cart = WC()->cart->find_product_in_cart($product_cart_id);

    // Get custom button text
    $custom_button_text = $is_in_cart ? get_option('cod_form_button_text', 'Completar orden') : get_option('cod_form_button_text', 'Pagar al recibir'); // Default to "Pagar al recibir"
    $custom_button_subtitle = get_option('cod_form_button_subtitle', 'Pago seguro'); // Default to "Pago seguro"

    // Get styles from plugin settings
    $background_color = get_option('cod_form_background_color', '#000000'); // Default to black
    $text_color = get_option('cod_form_text_color', '#ffffff'); // Default to white
    $text_size = get_option('cod_form_text_size', '1'); // Default to 1 rem
    $border_radius = get_option('cod_form_border_radius', '0'); // Default to 0 pixels
    $border_width = get_option('cod_form_border_width', '0'); // Default to 0px
    $border_color = get_option('cod_form_border_color', '#000000'); // Default to black
    $box_shadow = get_option('cod_form_box_shadow', '0'); // Default to 0 rem
    $button_width = get_option('cod_form_button_width', 'normal'); // Retrieve button width setting
    $button_animation = get_option('cod_form_button_animation', 'none'); // Default to none

    // Set button class based on whether the product is in the cart
    $button_class = $is_in_cart ? 'cod_continue_order' : 'cod_add_to_cart_button';

    echo '<div class="' . esc_attr($cod_form_button_sticky) . '">';
    
    // Add quantity input if the setting is not enabled
    if ($disable_quantity_selection === 'no') {
        echo '<input type="number" id="cod_quantity" name="quantity" value="1" min="1" style="width: 45px; padding: 5px; outline: none;">';
    }

    // Output custom button with styles and attributes
    echo '<button id="modal_checkout" class="button ' . esc_attr($button_class) . ' ' . esc_attr($button_animation) . '" data-product_id="' . esc_attr($product->get_id()) . '" data-current_url="' . esc_url(get_permalink()) . '" data-current_page_title="' . esc_attr(get_the_title()) . '" style="
        background-color: ' . esc_attr($background_color) . ';
        color: ' . esc_attr($text_color) . ';
        border-radius: ' . esc_attr($border_radius) . 'px;
        border-width: ' . esc_attr($border_width) . 'px;
        border-color: ' . esc_attr($border_color) . ';
        border-style: solid;
        padding: 10px 25px;
        box-shadow: 0 0 ' . esc_attr($box_shadow) . 'rem ' . esc_attr($box_shadow) . 'rem rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        float: none;
        width: ' . ($button_width === 'full' ? '100%' : 'auto') . ';
    ">';
    
    echo '<div style="
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        font-size: ' . esc_attr($text_size) . 'rem;
    ">
        <svg xmlns="http://www.w3.org/2000/svg" id="cart_icon_svg" data-name="Capa 1" version="1.1" viewBox="0 0 800 800" style="max-width: 17px; width:17px; height: auto;">
            <path style="fill:' . esc_attr($text_color) . ';" class="cls-1" d="M595,512l97.9-383.3h107.1v-60.3h-153.9l-23.1,90.4L0,158.3l66.2,353.7h528.8s0,0,0,0ZM607.6,219l-59.4,232.7H116.3l-43.7-233.1,535,.4Z"></path>
            <path style="fill:' . esc_attr($text_color) . ';" class="cls-1" d="M512.5,731.6c53.1,0,96.4-43.2,96.4-96.4s-43.2-96.4-96.4-96.4H149.5c-53.1,0-96.4,43.2-96.4,96.4s43.2,96.4,96.4,96.4,96.4-43.2,96.4-96.4-2.5-24.9-7-36.1h184.4c-4.5,11.2-7,23.3-7,36.1,0,53.1,43.2,96.4,96.4,96.4ZM185.6,635.2c0,19.9-16.2,36.1-36.1,36.1s-36.1-16.2-36.1-36.1,16.2-36.1,36.1-36.1c19.9,0,36.1,16.2,36.1,36.1ZM548.6,635.2c0,19.9-16.2,36.1-36.1,36.1s-36.1-16.2-36.1-36.1,16.2-36.1,36.1-36.1,36.1,16.2,36.1,36.1Z"></path>
        </svg>
        <span style="line-height:1;">' . esc_html($custom_button_text) . '</span>
    </div>';
    
    echo '<span style="font-size: 0.7rem; line-height:1.5;">' . esc_html($custom_button_subtitle) . '</span>';
    echo '</button>';
    echo '</div>';
    
    // Finaliza el buffer y devuelve el contenido
    ob_end_flush();
}
