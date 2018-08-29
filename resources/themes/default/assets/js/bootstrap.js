// Load a modern js utility library.
window._ = require('lodash');

// Load jQuery and Bootstrap jQuery plugin.
window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js').default;
require('bootstrap');

// Load the axios HTTP library which allows to easily issue requests.
window.axios = require('axios');

// Load Vue.js Framework.
window.Vue = require('vue');

// Configuration file.
require('./configure.js');

// Engine js files.
require('./engine.js');

// Personal theme or skin js functions.
require('./script.js');
