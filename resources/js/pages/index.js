import Vue from 'vue';

const req = require.context('./', true, /\.vue$/i);

req.keys().map(key => {
  const name = key.match(/[\w_-]+/)[0];
  return Vue.component(name, req(key).default)
});