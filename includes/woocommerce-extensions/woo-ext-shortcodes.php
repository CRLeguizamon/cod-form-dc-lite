<?php 
/**
 * WooCommerce Shortcodes Settings
 * 
 * Este archivo define varios shortcodes que puedes usar en páginas de productos o landing pages.
 * 
 */

// Define el shortcode [cod_menu_cart] para mostrar el ícono del carrito en el menú
function cod_menu_cart_shortcode($atts) {
    // Definir los atributos del shortcode y establecer un valor por defecto
    $atts = shortcode_atts(array(
        'color' => '', // Parámetro de color opcional
    ), $atts, 'cod_menu_cart');

    // Obtener el color del icono desde las opciones de configuración o de los atributos del shortcode
    $icon_color = !empty($atts['color']) ? $atts['color'] : get_option('cod_form_icon_color', '#000000'); // Color por defecto: negro
    $max_width = get_option('cod_form_icon_max_width', '30'); // Ancho máximo por defecto: 30px

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" id="cart_icon_svg" data-name="Capa 1" version="1.1" viewBox="0 0 800 800" style="
    max-width: ' . esc_attr($max_width) . 'px; width:100%; /* Aplicar el ancho máximo */
    height: auto; /* Mantener el alto automático */
    ">
        <path style="fill:' . esc_attr($icon_color) . ';" class="cls-1" d="M595,512l97.9-383.3h107.1v-60.3h-153.9l-23.1,90.4L0,158.3l66.2,353.7h528.8s0,0,0,0ZM607.6,219l-59.4,232.7H116.3l-43.7-233.1,535,.4Z"></path>
        <path style="fill:' . esc_attr($icon_color) . ';" class="cls-1" d="M512.5,731.6c53.1,0,96.4-43.2,96.4-96.4s-43.2-96.4-96.4-96.4H149.5c-53.1,0-96.4,43.2-96.4,96.4s43.2,96.4,96.4,96.4,96.4-43.2,96.4-96.4-2.5-24.9-7-36.1h184.4c-4.5,11.2-7,23.3-7,36.1,0,53.1,43.2,96.4,96.4,96.4ZM185.6,635.2c0,19.9-16.2,36.1-36.1,36.1s-36.1-16.2-36.1-36.1,16.2-36.1,36.1-36.1c19.9,0,36.1,16.2,36.1,36.1ZM548.6,635.2c0,19.9-16.2,36.1-36.1,36.1s-36.1-16.2-36.1-36.1,16.2-36.1,36.1-36.1,36.1,16.2,36.1,36.1Z"></path>
    </svg>';

    return '<button id="cod_menu_cart">' . $svg . '</button>';
}

// Shortcode para mostrar el botón "Añadir al carrito" en páginas externas como landing pages
function custom_add_to_cart_button_shortcode($atts) {
    if ( is_admin() || ( class_exists( 'Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) ) {
        return 'Edición'; // Evitar que se ejecute en el admin
    }
    
    // Extraer el ID de producto de los atributos del shortcode
    $atts = shortcode_atts(array(
        'id' => null,
    ), $atts, 'custom_add_to_cart_button');

    // Verificar si se proporcionó un ID de producto
    if (!$atts['id'] || !is_numeric($atts['id'])) {
        return ''; // Retorna vacío si no se proporcionó un ID válido
    }

    // Obtener el objeto del producto basado en el ID
    $product = wc_get_product($atts['id']);

    // Verificar si el producto es válido
    if (!$product) {
        return ''; // Retorna vacío si el producto no es válido
    }

    ob_start();
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');

    echo '<div class="shortcode_add_to_cart">';
    custom_single_product_button($product); // Usar la función personalizada en lugar de la predeterminada
    echo '</div>';

    add_filter('the_content', 'wpautop');
    add_filter('the_excerpt', 'wpautop');

    return ob_get_clean();

}

// Explicación de los Shortcodes
/**
 * - [add_to_cart_button]
 *   Este shortcode es para páginas de producto donde el botón de "Añadir al carrito" no aparece automáticamente, como cuando se usa un constructor visual. 
 *   Solo funciona en páginas de productos. Si no estás en una página de producto, no se mostrará nada.
 * 
 * - [custom_add_to_cart_button id="123"]
 *   Este shortcode es ideal para páginas de destino (landing pages) o cualquier otra página donde quieras mostrar un botón de "Añadir al carrito" para un producto específico. Debes proporcionar el ID del producto.
 * 
 *   Para obtener el ID del producto:
 *   1. Ve a **WooCommerce** > **Productos**.
 *   2. Selecciona el producto que deseas.
 *   3. En la barra de URL del navegador verás algo como `post=123`. El número `123` es el ID del producto.
 */

// Registrar los shortcodes
add_shortcode('custom_add_to_cart_button', 'custom_add_to_cart_button_shortcode');
add_shortcode('cod_menu_cart', 'cod_menu_cart_shortcode');

