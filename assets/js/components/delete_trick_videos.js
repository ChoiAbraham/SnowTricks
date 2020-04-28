import $ from "jquery";

export default function(message, videoid) {
    $('#deleteTrickVideos' + videoid).modal('show');
    $('#deleteTrickVideos'+ videoid).find('.modal-body').text(message);
};