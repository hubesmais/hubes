// js/title-limit.js
jQuery(document).ready(function($) {
    var maxLength = 80;

    function enforceTitleLength() {
        var titleField = $('#title');
        if (titleField.length) {
            titleField.on('keyup', function() {
                var title = $(this).val();
                if (title.length > maxLength) {
                    $(this).val(title.substring(0, maxLength));
                }
            });
        } else {
            console.error('Title field not found');
        }
    }

    enforceTitleLength();
});