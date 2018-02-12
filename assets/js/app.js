var $ = require('jquery');
require('bootstrap-sass');

import Vue from 'vue';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('transactions',  require('./components/TransactionsComponent.vue').default);

const app = new Vue({
    el: '#app'
});