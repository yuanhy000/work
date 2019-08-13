require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router";
import router from "./routes.js"

Vue.use(VueRouter);
Vue.component('Home', require('./components/Home.vue').default);
Vue.component('top-menu', require('./components/top-menu/top-menu.vue').default);
Vue.component('login', require('./components/login/login.vue').default);
Vue.component('register', require('./components/login/register.vue').default);
Vue.component('login-form', require('./components/login/login-email-form.vue').default);
Vue.component('register-form', require('./components/login/register-form.vue').default);

new Vue({
    el: '#app',
    router: router
});
