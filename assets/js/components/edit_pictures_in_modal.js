import $ from "jquery";

export default function(imageId) {
    $("#editPictures" + imageId).modal('show');
};