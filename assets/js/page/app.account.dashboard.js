import 'bootstrap';  // js-file

import '../../css/pages/app.account.dashboard.scss';
import '../bootstrap/browse_button_my_account'

import deleteInModal from '../components/delete_in_modal'
import $ from "jquery";

$(document).on('click', '.delete_account', function () {
    let message = $(this).data('message');
    deleteInModal(message);
});

$(document).on('click', '.delete_modal', function () {
    let slug = $(this).data('slug');
    let message = $(this).data('message');
    deleteInModal(slug, message);
});