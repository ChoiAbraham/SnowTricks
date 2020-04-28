import $ from "jquery";

$(function () {
    $(document).delegate('.checkbox_check', 'change', function (e) {
        let isChecked = $(this).is(':checked');
        if (isChecked){
            let checkboxes = $('.checkbox_check');
            let currentCheckbox = $(this).data('id');
            checkboxes.each(function (index, elt) {
                if ($(elt).data('id') !== currentCheckbox) {
                    $(elt).prop('checked', false);
                }
            });
        }
    })
});