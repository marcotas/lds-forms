import Vue from 'vue';

// users
Vue.component('users-form', require('./users/form').default);

// Admin
Vue.component('admin-users', require('./admin/users').default);

// Subscription
Vue.component('subscription-form', require('./subscription/form').default);

// Settings
Vue.component('settings-account', require('./settings/account').default);
Vue.component('settings-team', require('./settings/team-settings').default);