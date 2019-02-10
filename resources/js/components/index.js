import Vue from 'vue';

// users
Vue.component('users-form', require('./users/form'));

// Admin
Vue.component('admin-users', require('./admin/users'));

// Subscription
Vue.component('subscription-form', require('./subscription/form'));

// Settings
Vue.component('settings-account', require('./settings/account'));
Vue.component('settings-team', require('./settings/team-settings'));

// Services
Vue.component('services', require('./services/index'));

// Clients
Vue.component('clients', require('./clients/index'));

// Agenda
Vue.component('agenda-page', require('./agenda/page'));