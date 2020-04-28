import $ from "jquery";

$(document).on('click', '.edit_modal', function () {
    // let imageId = $(this).data('imageid');
    $('#deleteInModal').modal('show');
    $('#deleteInModal .modal-body').text(message);
});