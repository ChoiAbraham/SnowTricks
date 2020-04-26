import $ from "jquery";

export default function(imageId) {
    $("#editPictures" + imageId).modal('show');
    // $('#editInModal .modal-body').text(imageId);
};