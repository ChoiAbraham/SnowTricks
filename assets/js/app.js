/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

import Popper from 'popper.js';
import $ from 'jquery';
jQuery.noConflict();
import 'bootstrap';  // js-file
//import 'bootstrap/dist/css/bootstrap.css'; // css file
//or in .css @import '~bootstrap/dist/css/bootstrap.css'; or shortcut @import '~bootstrap';

//import getNiceMessage from './components/get_nice_message';

//console.log(getNiceMessage(5));



console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
