/** Load general dependencies from a file */
require('./bootstrap');
/** Load Vue based dependencies */
import Vue from 'vue';
import Vuetify from 'vuetify';
import { store } from './store/index';
/** Register Vue based dependencies */
Vue.use(Vuetify);
/** Set up Router */
import Router from './router';
/** Register Vue components */
Vue.component('dashboard', require('./Dashboard.vue'));
Vue.component('login', require('./Login.vue'));

/** Register Vue filters */
import DateFilter from './filters/date';
import DateMinusYearFilter from './filters/dateMinusYear';
Vue.filter('date', DateFilter);
Vue.filter('dateMinusYear', DateMinusYearFilter);

Vue.config.productionTip = false;

/** Mount Vue instance */
const app = new Vue({
    el: '#app',
    router: Router,
    store: store
});