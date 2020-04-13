import $ from "jquery";

export default function(message) {
    $('#deleteInModal').modal('show');
    $('#deleteInModal .modal-body').text(message);
};