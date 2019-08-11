require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router";
import router from "./routes.js"

Vue.use(VueRouter);
Vue.component('Home', require('./components/Home.vue').default);

new Vue({
    el: '#app',
    router: router
});
