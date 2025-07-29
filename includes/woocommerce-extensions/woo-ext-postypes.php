<?php
/**
 * Register Custom Post Types for COD Offers
 */

function cod_register_custom_post_types() {
    // Define la URL del ícono SVG
    $icon_svg = MODALCODF_DC_ASSETS_URL . 'img/logo-modal-cash-on-delivery-checkout-wp-icon.svg';
    
    // Register a custom menu page for COD Offers
    add_menu_page(
        esc_html__( 'Modal COD', 'modal-cod-form' ),
        'COD Offers',
        'manage_options',
        'cod-offers',
        'cod_offers_page_content',
        $icon_svg, // Aquí se usa el ícono SVG desde la URL definida
        6
    );
}

/**
 * Enqueue admin styles for COD Form menu icon
 * 
 * Esta función carga estilos CSS específicos para el área de administración,
 * incluyendo el ajuste del tamaño del ícono SVG en el menú de WordPress.
 * Se ejecuta en todas las páginas de administración donde el menú sea visible.
 * 
 * @since 1.0.0
 * @param string $hook The current admin page hook
 */
function cod_enqueue_admin_styles($hook) {
    // Registrar y encolar estilo para el icono del menú de administración
    // Se ejecuta en todas las páginas de admin donde el menú sea visible
    wp_register_style('cod-admin-menu-icon', false);
    wp_enqueue_style('cod-admin-menu-icon');
    
    // Agregar CSS inline para el icono del menú usando wp_add_inline_style()
    $custom_css = '
        #adminmenu #toplevel_page_cod-offers div.wp-menu-image img {
            width: 19px;
            height: auto;
        }
    ';
    wp_add_inline_style('cod-admin-menu-icon', $custom_css);
}

add_action('admin_enqueue_scripts', 'cod_enqueue_admin_styles');

// Display content for the COD Offers page
function cod_offers_page_content() {
    // Definir el logo SVG
    $icon_svg = MODALCODF_DC_ASSETS_URL . 'img/logo-modal-cash-on-delivery-checkout.svg';
    
    echo '<div class="wrap">';
    
    // Header con logo y título
    echo '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; margin-bottom: 25px;">';
    echo '<div style="display: flex; align-items: center;">';
    echo '<img src="' . esc_url($icon_svg) . '" alt="COD Logo" style="max-width: 60px; height: auto; margin-right: 20px;">';
    echo '<h1 style="margin: 0; font-weight: bold; color: #101538;">Modal COD Form for WooCommerce</h1>';
    echo '</div>';
    echo '</div>';

    // Contenedor principal de 2 columnas
    echo '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">';
    
    // ===== COLUMNA IZQUIERDA - INFORMACIÓN =====
    echo '<div>';
    
    // Información del plugin
    echo '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; padding: 25px; margin-bottom: 25px;">';
    echo '<p style="margin: 0 0 15px 0; color: #555; line-height: 1.6;">' . esc_html__('Con este plugin, el botón "Agregar al carrito" de WooCommerce se transforma en un botón personalizable que abre una ventana modal con un formulario sencillo.', 'modal-cod-form') . '</p>';
    
    echo '<h3 style="margin: 15px 0 10px 0; color: #101538; font-size: 16px;">Características incluidas:</h3>';
    
    // Lista de características
    $lite_features = array(
        'Formulario modal personalizable',
        'Campos de información del cliente',
        'Envíos gratis o envíos nativos de WooCommerce',
        'Integración con Woocommerce',
        'Agrega el botón de pago a cualquier página con shortcodes',
        'Ícono de carrito personalizable para el menú',
        'Agrega imagen superior e inferior en el formulario',
        'Personaliza el botón de pago al 100%'
    );
    
    echo '<div style="display: grid; gap: 15px;">';
    foreach ($lite_features as $feature) {
        echo '<div style="padding: 8px 0; border-bottom: 1px solid #e9ecef; display: flex; align-items: center;">';
        echo '<span style="color: #28a745; font-weight: bold; margin-right: 10px; font-size: 14px;">•</span>';
        echo '<span style="color: #555; font-size: 14px;">' . esc_html($feature) . '</span>';
        echo '</div>';
    }
    echo '</div>';
    
    echo '<div style="margin-top: 20px;">';
    echo '<a href="' . esc_url(admin_url('admin.php?page=wc-settings&tab=cod_form')) . '" class="button button-primary" style="background: #667eea; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold; text-decoration: none; border: none;">⚙️ CONFIGURAR PLUGIN</a>';
    echo '</div>';
    echo '</div>';

    // Información adicional
    echo '<div style="background: #fff3cd; border: 1px solid #ffba00; border-radius: 8px; padding: 20px;">';
    echo '<h3 style="margin: 0 0 10px 0; color: #101538; font-size: 16px;">💡 Acerca del Proyecto</h3>';
    echo '<p style="margin: 0; color: #555; font-size: 14px; line-height: 1.5;">' . esc_html__('Este plugin se realizó con la intención de aportar herramientas útiles a los dropshippers y vendedores con modalidad contra entrega. Está en constante evolución. Si tienes ideas o sugerencias, no dudes en contactarme en', 'modal-cod-form') . ' <a href="mailto:hola@mcodform.com" style="color: #101538; font-weight: bold;">hola@mcodform.com</a>. ¡Tu colaboración es muy valorada!</p>';
    echo '</div>';
    
    echo '</div>'; // Fin columna izquierda
    
    // ===== COLUMNA DERECHA - PROMOCIÓN PRO =====
    echo '<div>';
    
    // Banner promocional PRO
    echo '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 25px; margin-bottom: 25px; color: white; text-align: center;">';
    echo '<h2 style="margin: 0 0 15px 0; color: white; font-size: 20px;">' . esc_html(MODALCODF_PRO_TITLE) . '</h2>';
    echo '<p style="margin: 0 0 15px 0; font-size: 16px;">' . esc_html(MODALCODF_PRO_SUBTITLE) . '</p>';
    echo '<div style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 8px; margin-bottom: 15px;">';
    echo '<span style="font-size: 24px; font-weight: bold; color: #ffeb3b;">🎫 ' . esc_html(MODALCODF_PRO_COUPON) . '</span>';
    echo '</div>';
    echo '<div style="margin-bottom: 20px;">';
    echo '<span style="background-color: #ffeb3b; color: #101538; padding: 5px 10px; border-radius: 5px; margin-right: 10px; font-weight: bold;">25% OFF</span>';
    echo '<span style="text-decoration: line-through; opacity: 0.8; margin-right: 10px;">40 USD</span>';
    echo '<span style="font-size: 24px; font-weight: bold;">30 USD</span>';
    echo '</div>';
    echo '<a href="' . esc_url(MODALCODF_PRO_URL_BASE . '&utm_medium=offers-page') . '" target="_blank" class="button" style="background: white; color: #667eea; padding: 12px 25px; border-radius: 6px; font-weight: bold; text-decoration: none; border: none; font-size: 16px;">' . esc_html(MODALCODF_PRO_BUTTON_TEXT) . '</a>';
    echo '<p style="margin: 15px 0 0 0; font-size: 14px; opacity: 0.9;">' . esc_html(MODALCODF_PRO_GUARANTEE) . '</p>';
    echo '</div>';

    // Características PRO detalladas
    echo '<div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; padding: 25px;">';
    echo '<h3 style="margin: 0 0 20px 0; color: #101538; font-size: 18px;">🚀 Características Exclusivas PRO</h3>';
    
    echo '<div style="display: grid; gap: 15px;">';
    
    // Lista de características
    $features = array(
        'Ciudades y departamentos automáticos para 7 países',
        'Ofertas de cantidad (2x1, 3x2, etc.)',
        'Downsells para recuperar ventas perdidas',
        'Webhook para automatizaciones (n8n, Make)',
        'Botón secundario personalizado',
        'Compatible con Dropi y Effy',
        'Shortcodes para landing pages',
        'Redirección personalizada post-compra',
        'Botón junto al "Añadir al carrito"'
    );
    
    foreach ($features as $feature) {
        echo '<div style="padding: 8px 0; border-bottom: 1px solid #e9ecef; display: flex; align-items: center;">';
        echo '<span style="color: #28a745; font-weight: bold; margin-right: 10px; font-size: 14px;">•</span>';
        echo '<span style="color: #555; font-size: 14px;">' . esc_html($feature) . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
    
    echo '<div style="margin-top: 20px; text-align: center;">';
    echo '<a href="https://demo-mcod.devcristian.com/" target="_blank" style="color: #667eea; text-decoration: none; font-weight: bold;">🎯 Ver Demostración en Vivo</a>';
    echo '</div>';
    
    echo '</div>';
    
    echo '</div>'; // Fin columna derecha
    echo '</div>'; // Fin grid de 2 columnas
    
    echo '</div>'; // Fin wrap
}


add_action( 'init', 'cod_register_custom_post_types' );
