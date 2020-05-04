import $ from "jquery";

export default function(message, imageId) {
    $('#deleteTrickPictures'+imageId).modal('show');
    $('#deleteTrickPictures'+imageId).find('.modal-body').text(message);
}