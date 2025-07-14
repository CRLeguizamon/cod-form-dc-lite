jQuery(document).ready(function($) {
   
    // Manejar el envío del formulario de pago
    $('body').on('submit', '#checkout_form', function(e) {
        e.preventDefault();

        var formIsValid = true;
        var errorMessage = '';
        
        // Validar campos requeridos
        $('#checkout_form input[required], #checkout_form select[required]').each(function() {
            if ($(this).val() === '') {
                formIsValid = false;
                errorMessage += $(this).prev('label').text() + ' es requerido.\n';
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Validar que se haya seleccionado un método de envío
        if (!$('#state').hasClass('free_shipping')) {
            if (!$('input[name="shipping_method"]:checked').length) {
                formIsValid = false;
                errorMessage += 'Por favor selecciona un método de envío.\n';
                $('#shipping-methods').addClass('is-invalid');
            } else {
                $('#shipping-methods').removeClass('is-invalid');
            }
        }
        
        if (formIsValid) {
            
            var shippingMethod = $('input[name="shipping_method"]:checked').val();
            var shippingCode = $('input[name="shipping_method"]:checked').data('shipping-code');
            
            // Si es free_shipping, forzar valores de envío gratuitos
            if ($('#state').hasClass('free_shipping')) {
                shippingMethod = 'free_shipping';
                shippingCode = ''; // No necesita código cifrado para envío gratis
            }
            
            var formData = {
                action: 'cod_create_order',
                _wpnonce: $('#checkout_form input[name="_wpnonce"]').val(),
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                address_2: $('#address_2').val() ? $('#address_2').val() : '',
                state: $('#state option:selected').val(),
                city: $('#city').hasClass('select_active_districts') ? $('#city option:selected').text() : $('#city').val(),
                district: $('#district').val() ? $('#district').val() : '',
                email: $('#email').val(),
                shipping_method: shippingMethod,
                shipping_code: shippingCode
            };

            // Añadir order_comments solo si no está deshabilitado
            if (!$('#order_comments').prop('disabled')) {
                formData.order_comments = $('#order_comments').val();
            }

            // Añadir terms_checkbox solo si no está deshabilitado
            if (!$('#terms_checkbox').prop('disabled')) {
                formData.terms_checkbox = $('#terms_checkbox').is(':checked');
            }

            $.ajax({
                url: cod_form_ajax.ajax_url,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                utils.showLoader(); // Muestra el loader antes de enviar la solicitud
                $('#codFormModal button[type="submit"]').attr('disabled', true);
                },
                success: function(response) {
                    
                    if (response.success) {
                        var newUrl = new URL(response.data.success_redirect_url);
                        newUrl.searchParams.append('cod_total', response.data.total_amount);
                        window.location.href = newUrl.toString();
                    } else {
                        window.location.href = response.data.error_redirect_url;
                    }
                },
                complete: function(){
                    utils.hideLoader(); // Oculta el loader cuando la solicitud es exitosa
                    $('#codFormModal button[type="submit"]').removeAttr('disabled');
                    
                }
            });
        } else {
            alert(errorMessage);
        }
    });

    // Manejar el cambio en la selección del departamento
    $('body').on('change', '.select_state', function() {
        var state = $(this).val();
        $.ajax({
            url: cod_form_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_shipping_methods',
                state: state
            },
            success: function(response) {
                $('#shipping-methods').html(response);
                
            },
            complete: function(){
                //utils.calculateShipping($('input[name="shipping_method"]'));
            }
        });
    });

    
});
