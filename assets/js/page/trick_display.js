import '../../css/pages/trick.display.scss'

jQuery.noConflict();
import 'bootstrap';  // js-file

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
}