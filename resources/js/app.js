/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import axios from 'axios';
import Vue from 'vue';
import ToggleButton from 'vue-js-toggle-button';
import { loadProgressBar } from 'axios-progress-bar';
import VueAvatar from 'vue-avatar';

Vue.use(ToggleButton);
Vue.component('avatar', VueAvatar);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.Vue = require('vue');

require('./commons');
require('./filters');
require('./directives');
require('./components');

require('./components/table');
require('./components/dialogs');
require('./components/forms');

// Resources
require('./minutes');
require('./recipes');
require('./users');
require('./topics');

Vue.prototype.$route = window.laroute.route;
Vue.prototype.$http = window.axios;
Vue.prototype.$axios = axios;
Vue.prototype.$obj_get = (obj, str) => {
    return str.split('.').reduce((a, c) => (a ? a[c] : null), obj);
};

loadProgressBar();

new Vue({
    el: '#app'
});
