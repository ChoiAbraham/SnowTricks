import 'bootstrap';
import $ from "jquery";

$(document).on('click', '.js-delete-comment', function () {
    let message = $(this).data('message');
    let id = $(this).data('id');
    $('#deleteComment'+id).modal('show');
    $('#deleteComment'+id).find('.modal-body').text(message);
});
