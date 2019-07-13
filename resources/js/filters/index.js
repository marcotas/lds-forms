import Vue from 'vue';
import date from './date';
import from_now from './from_now';
import capitalize from './capitalize';
// import gender from './gender';

Vue.filter('date', date);
Vue.filter('from_now', from_now);
Vue.filter('capitalize', capitalize);
Vue.filter('gender', require('./gender').default);
