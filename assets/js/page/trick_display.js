import '../../css/pages/trick.display.scss';

jQuery.noConflict();
import 'bootstrap';
import $ from "jquery";
import editFirstPictureModal from "../components/edit_in_modal";  // js-file
import editPictures from "../components/edit_pictures_in_modal";  // js-file
import editVideos from "../components/edit_videos_in_modal";  // js-file
import deleteTrickVideos from "../components/delete_trick_videos";  // js-file
import deleteTrickPictures from "../components/delete_trick_pictures";  // js-file
import deleteFirstPicture from "../components/delete_first_picture";  // js-file

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
}

$('.show-media').on('click', function () {
    $('.ondesktop-only').removeClass('hide-block');
    $('.ondesktop-only').toggle();
});

//*** MODALS ***//

//show modal for Edit first image
$(document).on('click', '.edit_modal', function () {
    // let imageId = $(this).data('imageid');
    editFirstPictureModal();
});

//show modal for Edit trick images
$(document).on('click', '.edit_pictures_modal', function () {
    let imageId = $(this).data('imageid');
    editPictures(imageId);
});

//show modal for Edit trick videos
$(document).on('click', '.edit_videos_modal', function () {
    let videoId = $(this).data('videoid');
    editVideos(videoId);
});

//show modal for Delete trick images
$(document).on('click', '.deleteTrickPictures', function () {
    let imageId = $(this).data('imageid');
    let message = $(this).data('message');
    deleteTrickPictures(message, imageId);
});

//show modal for Delete trick videos
$(document).on('click', '.deleteTrickVideos', function () {
    let videoId = $(this).data('videoid');
    let message = $(this).data('message');
    deleteTrickVideos(message, videoId);
});

//show modal for Delete trick images
$(document).on('click', '.deleteFirstPicture', function () {
    let message = $(this).data('message');
    deleteFirstPicture(message);
});


//**** DELETE ****//

// Delete First Picture
$(document).on('click', '.js-delete-first-picture', function (e) {
    e.preventDefault();
    $('.js-first-picture-wrapper').remove();
});

// Delete Trick Pictures
$(document).on('click', '.js-delete-picture', function (e) {
    e.preventDefault();
    let imageId = $(this).data('imageid');
    $('.js-picture-wrapper'+imageId).remove();
});

// Delete Trick Videos
$(document).on('click', '.js-delete-video', function (e) {
    e.preventDefault();
    let videoId = $(this).data('videoid');
    $('.js-video-wrapper'+videoId).remove();
});



