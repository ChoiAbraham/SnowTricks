import $ from "jquery";

export default function(videoId) {
    $("#editVideos" + videoId).modal('show');
}