jQuery(document).ready(function($) {
    // Función para manejar el clic en el contenedor del checkbox
    function handleCheckboxClick(e) {
        // Evitar que el clic se propague al checkbox
        e.preventDefault();
        e.stopPropagation();

        // Obtener el checkbox dentro del contenedor
        const checkbox = $(this).find('input[type="checkbox"]');
        
        // Cambiar el estado del checkbox
        checkbox.prop('checked', !checkbox.prop('checked'));
        
        // Disparar el evento change para que WooCommerce detecte el cambio
        checkbox.trigger('change');
    }

    // Agregar el evento click a los contenedores de checkbox
    $('.cod-form-settings .form-table td:has(input[type="checkbox"])').on('click', handleCheckboxClick);

    // Prevenir que el clic en el checkbox propague al contenedor
    $('.cod-form-settings .form-table input[type="checkbox"]').on('click', function(e) {
        e.stopPropagation();
    });

    // Inicializar el selector de medios de WordPress
    var mediaUploader;

    $('.cod-form-settings .media-upload-button').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var input = button.siblings('input[type="text"]');
        var preview = button.siblings('.media-preview');

        // Si el selector de medios ya existe, reabrirlo
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Crear el selector de medios
        mediaUploader = wp.media({
            title: 'Seleccionar Imagen',
            button: {
                text: 'Usar esta imagen'
            },
            multiple: false
        });

        // Cuando se selecciona una imagen
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            preview.html('<img src="' + attachment.url + '" style="max-width:100px;height:auto;" />');
        });

        // Abrir el selector de medios
        mediaUploader.open();
    });
}); 