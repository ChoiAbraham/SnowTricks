import $ from "jquery";

export default function(message) {
    $('#deleteFirstPicture').modal('show');
    $('#deleteFirstPicture .modal-body').text(message);
}