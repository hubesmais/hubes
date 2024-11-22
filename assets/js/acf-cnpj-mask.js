jQuery(document).ready(function($) {
    // Function to apply the CNPJ mask
    function applyCNPJMask(cnpj) {
        if (cnpj) {
            // Remove any non-numeric characters
            cnpj = cnpj.replace(/\D/g, '');

            // Limit to 14 digits
            cnpj = cnpj.substring(0, 14);

            // Apply CNPJ format: XX.XXX.XXX/XXXX-XX
            cnpj = cnpj.replace(/^(\d{2})(\d)/, "$1.$2");
            cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            cnpj = cnpj.replace(/\.(\d{3})(\d)/, ".$1/$2");
            cnpj = cnpj.replace(/(\d{4})(\d)/, "$1-$2");

            return cnpj;
        }
        return ''; // Return empty string if the value is undefined or empty
    }

    // Apply the mask on all fields with the specific data-key attribute (dynamically added rows included)
    $(document).on('input', 'td[data-key="field_66bb8969fc94a"] input', function() {
        var formattedCNPJ = applyCNPJMask($(this).val());
        $(this).val(formattedCNPJ);
    });

    // Reapply the mask on page load for all matching fields
    $('td[data-key="field_66bb8969fc94a"] input').each(function() {
        var initialCNPJValue = $(this).val();
        if (initialCNPJValue) {
            $(this).val(applyCNPJMask(initialCNPJValue));
        }
    });

    // Listen for form submission to ensure the masked value is submitted
    $('#publish').on('click', function() {
        $('td[data-key="field_66bb8969fc94a"] input').each(function() {
            var formattedCNPJ = applyCNPJMask($(this).val());
            $(this).val(formattedCNPJ); // Update the field with the formatted value before submitting
        });
    });
});