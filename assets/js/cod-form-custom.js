jQuery(document).ready(function($) {
    
    // HTML del modal y loader
    var modalHtml = '<div id="codFormModal" class="cod-modal"><div class="cod-modal-content"></div></div>';
    var loaderHtml = '<div class="loader_container"><svg style="width: 80px; height: 80px; padding: 20px; border-radius: 50%; background: #f7f7f7;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><radialGradient id="a11" cx=".66" fx=".66" cy=".3125" fy=".3125" gradientTransform="scale(1.5)"><stop offset="0" stop-color="#5E5E5E"></stop><stop offset=".3" stop-color="#5E5E5E" stop-opacity=".9"></stop><stop offset=".6" stop-color="#5E5E5E" stop-opacity=".6"></stop><stop offset=".8" stop-color="#5E5E5E" stop-opacity=".3"></stop><stop offset="1" stop-color="#5E5E5E" stop-opacity="0"></stop></radialGradient><circle transform-origin="center" fill="none" stroke="url(#a11)" stroke-width="15" stroke-linecap="round" stroke-dasharray="200 1000" stroke-dashoffset="0" cx="100" cy="100" r="70"><animateTransform type="rotate" attributeName="transform" calcMode="spline" dur="0.5" values="360;0" keyTimes="0;1" keySplines="0 0 1 1" repeatCount="indefinite"></animateTransform></circle><circle transform-origin="center" fill="none" opacity=".2" stroke="#5E5E5E" stroke-width="15" stroke-linecap="round" cx="100" cy="100" r="70"></circle></svg></div>';
    var wooDecimals = utils.getWooDecimalsSettings();
   
    // Agregar el modal y loader después de la etiqueta body
    $('body').prepend(modalHtml + loaderHtml);
    
    $('body').on('click', '.cod_add_to_cart_button', function(e) {
        e.preventDefault();

        var button = $(this);
        var product_id, variation_id = 0;
        var quantity = 1; // Default quantity is 1

        // Check if the product is variable
        if (button.hasClass('variable')) {
            product_id = button.closest('form').find('.product_id').val();
            variation_id = button.closest('form').find('.variation_id').val();
            quantity = button.closest('form').find('input.qty').val() || 1;
            
            // Validar si se ha seleccionado una variación
            if (variation_id == 0 || !variation_id) {
                alert('Selecciona las opciones del producto');
                return; // No continuar si no se ha seleccionado una variación
            }
        } else {
            product_id = button.data('product_id');
            quantity = button.closest('form').find('input.qty').val() || 1;
        }

        // Check if quantity input exists and get its value
        var quantityInput = $('#cod_quantity');
        var wooQuantityInput = $('.input-text.qty');
        
        // Si existe el input personalizado, usar ese valor
        if (quantityInput.length > 0) {
            quantity = quantityInput.val();
        } 
        // Si no existe el input personalizado pero existe el de WooCommerce, usar ese
        else if (wooQuantityInput.length > 0) {
            quantity = wooQuantityInput.val();
        }

        $.ajax({
            url: cod_form_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'cod_form_add_to_cart',
                product_id: product_id,
                variation_id: variation_id,
                quantity: quantity,
                _wpnonce: cod_form_ajax.add_to_cart_nonce
            },
            beforeSend: function() {
                utils.showLoader(); // Muestra el loader antes de enviar la solicitud
            },
            success: function(response) {
                utils.hideLoader(); // Oculta el loader cuando la solicitud es exitosa
                if (response.success) {
                    $('.cart-contents .count').text(response.data.cart_count);
                    changeClassAction('cod_add_to_cart_button', 'cod_continue_order');
                    openEmptyModal();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    $('body').on('click', '.cod_continue_order', function(e) {
        e.preventDefault();
        openEmptyModal();
    });

    $('body').on('click', '.button_modal_close', function() {
        $('#codFormModal').fadeOut();
        $('body').removeClass('cod_scroll_hidden');
    });

    function changeClassAction(deleteclass, newclass) {
        $('.' + deleteclass).addClass(newclass);
        $('.' + newclass).removeClass(deleteclass);
    }
    
    // Evento de clic en el ícono del carrito
    $('body').on('click', '#cod_menu_cart', function(e) {
        e.preventDefault();

        $.ajax({
            url: cod_form_ajax.ajax_url,
            type: 'GET', // Cambiar a GET porque load_modal_content no espera datos POST
            data: {
                action: 'load_modal_content', // Usar la acción existente
                _wpnonce: cod_form_ajax.load_modal_nonce
            },
            success: function(response) {
                $('.cod-modal-content').html(response);
                $('#codFormModal').fadeIn();
                $('body').addClass('cod_scroll_hidden');
                
            },
            error: function(error) {
                console.error(error); 
            }
        });
    });
    
    function openEmptyModal() {
        // Obtener los datos del botón si existen en la página
        var $button = $('#modal_checkout');
        var current_url = $button.data('current_url') || window.location.href;
        var current_page_title = $button.data('current_page_title') || document.title;
        var product_id = $button.data('product_id') || '';

        $.ajax({
            url: cod_form_ajax.ajax_url,
            type: 'GET',
            data: {
                action: 'load_modal_content',
                current_url: current_url,
                current_page_title: current_page_title,
                product_id: product_id,
                _wpnonce: cod_form_ajax.load_modal_nonce
            },
            success: function(response) {
                $('.cod-modal-content').html(response);
                $('#codFormModal').fadeIn();
                $('body').addClass('cod_scroll_hidden');
            }
        });
    }

    // Detecta cambios en el campo de variación
    $('body').on('change', '.variation_id', function() {
        var variation_id = $(this).val();
        $('#modal_checkout').attr('data-product_id', variation_id);
    });

    // Evento al hacer clic en el botón "remove-product"
    $('body').on('click', '.remove-product', function(e) {
        e.preventDefault();

        var cart_item_key = $(this).data('cart_item_key');

        $.ajax({
            url: cod_form_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'remove_product_from_cart',
                cart_item_key: cart_item_key,
                quantity: 1, // Cantidad a eliminar
                _wpnonce: cod_form_ajax.remove_product_nonce
            },
            beforeSend: function() {
                utils.showLoader(); // Muestra el loader antes de enviar la solicitud
            },
            success: function(response) {
                utils.hideLoader(); // Oculta el loader cuando la solicitud es exitosa
                if (response.success) {
                    
                    // Actualizar la tabla del carrito con los nuevos datos
                    $('.cod-woocommerce-cart-table').html(response.data.new_cart_content);

                    // Actualizar subtotal y total con envío
                    var shippingAmount = utils.parseFormattedPrice($('.shipping-amount').text());
                    var subtotal = parseFloat(response.data.subtotal);
                    
                    var total = subtotal + shippingAmount;
                    
                    $('.subtotal-amount').text(utils.formatPrice(subtotal));
                    $('.total-amount').text(utils.formatPrice(total));
                    $('.shipping-amount').text(utils.formatPrice(shippingAmount));
                    
                    // Verificar si el carrito está vacío y cerrar el modal si es así
                    if (response.data.cart_count === 0) {
                        $('#codFormModal').fadeOut();
                        $('body').removeClass('cod_scroll_hidden');
                        changeClassAction('cod_continue_order', 'cod_add_to_cart_button');
                    }
                } else {
                    alert(response.data.message);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    // Manejar la selección de métodos de envío
    $('body').on('change', 'input[name="shipping_method"]', function() {
        var shippingCost = parseFloat($(this).data('cost')) || 0;
        var subtotal = utils.parseFormattedPrice($('.subtotal-amount').text());
        
        var total = subtotal + shippingCost;

        $('.shipping-amount').text(utils.formatPrice(shippingCost));
        $('.total-amount').text(utils.formatPrice(total));
    });
    

    
    
});
