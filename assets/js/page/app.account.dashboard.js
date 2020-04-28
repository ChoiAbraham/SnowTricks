import 'bootstrap';  // js-file

import '../../css/pages/app.account.dashboard.scss';
import '../bootstrap/browse_button_my_account'

import $ from "jquery";

$(document).on('click', '.delete_account', function () {
    let message = $(this).data('message');
    $('#deleteInModal').modal('show');
    $('#deleteInModal .modal-body').text(message);
});