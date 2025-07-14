class Utils {
    // Función para decodificar entidades HTML
    decodeHtmlEntity(str) {
        var txt = document.createElement("textarea");
        txt.innerHTML = str;
        return txt.value;
    }
    
    getWooDecimalsSettings(){
        return cod_form_ajax.number_of_decimals;
    }

    getWooDecimalSeparator(){
        return cod_form_ajax.decimal_separator || '.';
    }

    getWooThousandSeparator(){
        return cod_form_ajax.thousand_separator || ',';
    }

    getWooCurrencySymbol(){
        return cod_form_ajax.currency_symbol || '$';
    }

    // Función personalizada para formatear números usando los separadores de WooCommerce
    codFormatCurrency(number) {
        const decimals = this.getWooDecimalsSettings();
        const decimalSeparator = this.getWooDecimalSeparator();
        const thousandSeparator = this.getWooThousandSeparator();
        
        // Asegurar que number es un número
        const num = parseFloat(number);
        
        // Formatear con los decimales correctos
        const parts = num.toFixed(decimals).split('.');
        
        // Agregar separador de miles
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
        
        // Unir con el separador decimal de WooCommerce
        return parts.join(decimalSeparator);
    }

    // Función para formatear un precio completo con símbolo de moneda
    formatPrice(amount) {
        const formattedAmount = this.codFormatCurrency(amount);
        const currencySymbol = this.getWooCurrencySymbol();
        return currencySymbol + formattedAmount;
    }

    // Función para parsear un precio formateado de vuelta a número
    parseFormattedPrice(formattedText) {
        if (!formattedText) return 0;
        
        const decimalSeparator = this.getWooDecimalSeparator();
        const thousandSeparator = this.getWooThousandSeparator();
        const currencySymbol = this.getWooCurrencySymbol();
        
        // Remover el símbolo de moneda y espacios
        let cleanText = formattedText.replace(currencySymbol, '').trim();
        
        // Si el separador de miles y decimal son diferentes, procesamos correctamente
        if (thousandSeparator !== decimalSeparator) {
            // Primero removemos todos los separadores de miles
            const thousandSeparatorRegex = new RegExp('\\' + thousandSeparator, 'g');
            cleanText = cleanText.replace(thousandSeparatorRegex, '');
            
            // Luego reemplazamos el separador decimal con punto para parseFloat
            if (decimalSeparator !== '.') {
                const decimalSeparatorRegex = new RegExp('\\' + decimalSeparator, 'g');
                cleanText = cleanText.replace(decimalSeparatorRegex, '.');
            }
        } else {
            // Si ambos separadores son iguales (caso raro), remover todo excepto números y el último separador
            const parts = cleanText.split(decimalSeparator);
            if (parts.length > 1) {
                // El último es el decimal, los anteriores son miles
                const wholePart = parts.slice(0, -1).join('');
                const decimalPart = parts[parts.length - 1];
                cleanText = wholePart + '.' + decimalPart;
            }
        }
        
        // Remover cualquier carácter que no sea número, punto o guión
        cleanText = cleanText.replace(/[^0-9.-]/g, '');
        
        return parseFloat(cleanText) || 0;
    }

    // Función para mostrar el loader
    showLoader() {
        jQuery('.loader_container').css('display', 'flex');
    }

    // Función para ocultar el loader
    hideLoader() {
        jQuery('.loader_container').hide();
    }
    
    applyDiscount(discountValue, discountType, price) {
        var finalPrice = price; // Precio original

        if (discountType === 'percentage') {
            // Si el descuento es porcentual, calculamos el porcentaje sobre el precio
            var discountAmount = (price * discountValue) / 100;
            finalPrice = price - discountAmount;
        } else if (discountType === 'fixed') {
            // Si el descuento es fijo, restamos el valor del descuento del precio
            finalPrice = price - discountValue;
        }else{
            finalPrice = price;
        }

        // Asegurarse de que el precio final no sea menor que 0
        return finalPrice > 0 ? finalPrice : 0;
    }
    calculateShipping(a){
        var shippingCost = parseFloat(a.data('cost')) || 0;
        var subtotal = this.parseFormattedPrice(jQuery('.subtotal-amount').text());
        
        var total = subtotal + shippingCost;

        jQuery('.shipping-amount').text(this.formatPrice(shippingCost));
        jQuery('.total-amount').text(this.formatPrice(total));
    }
}

// Instanciar la clase y agregarla al ámbito global
window.utils = new Utils();
