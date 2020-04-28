import 'bootstrap';
import $ from "jquery";

$(document).on('click', '.js-delete-trick', function () {
    let message = $(this).data('message');
    let id = $(this).data('id');
    $('#deleteInModal'+id).modal('show');
    $('#deleteInModal'+id).find('.modal-body').text(message);
});