import $ from "jquery";

export default function(message) {
    $('#deleteTrickPictures').modal('show');
    $('#deleteTrickPictures .modal-body').text(message);
};