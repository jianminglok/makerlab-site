window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

import Vue from 'vue';
import router from './router';
import App from './app.vue';

//import App from './component/select.vue';


Vue.use(router);


const app = new Vue({
  router,
  el: '#app',
  render: h => h(App)
});