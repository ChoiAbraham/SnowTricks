import $ from "jquery";

export default function(message) {
    $('#deleteTrickVideos').modal('show');
    $('#deleteTrickVideos .modal-body').text(message);
};