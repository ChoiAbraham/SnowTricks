import $ from 'jquery';

$(document).ready(function() {
    $('#js-btn-morecomments').on('click', function () {
        $('#js-btn-loading').removeClass('hide-block');
        $(this).hide();

        let maxpage = $(this).data('maxpage');

        let page = $(this).data('page');
        let url = $(this).data('url') + '?page=' + page;
        $(this).data('page', page + 1);

        var self = this;
        $.ajax({
            method: 'GET',
            url: url,
            success: (response) => {
                $(self).show();
                $('#js-btn-loading').addClass('hide-block');

                $('#comments-list').append(response);
                if(page >= maxpage) {
                    $(self).remove();
                }
            }
        });
    });

    $('.show-media').on('click', function () {
        $('.toshow').toggle();
    });

    $('.delete').on('click', function () {
        let message = $(this).data('message');
        deleteInModal(message);
    });
});

