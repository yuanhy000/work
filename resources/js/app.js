require('./bootstrap');

window.Vue = require('vue');

import VueRouter from "vue-router"
import router from "./routes.js"
import store from './store/index'
import jwtToken from './helpers/jwt'
import zh_CN from './locale/zh_CN.js';
import VeeValidate, {Validator} from 'vee-validate';


Vue.use(VeeValidate);
Validator.localize('zh_CN', zh_CN);

Vue.use(VueRouter);

axios.interceptors.request.use(function (config) {
    if (jwtToken.getToken()) {
        config.headers['Authorization'] = 'Bearer ' + jwtToken.getToken();
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});


Vue.component('app', require('./components/App.vue').default);
Vue.component('top-menu', require('./components/top-menu/top-menu.vue').default);
Vue.component('login', require('./components/login/login.vue').default);
Vue.component('register', require('./components/login/register.vue').default);
Vue.component('login-form', require('./components/login/login-email-form.vue').default);
Vue.component('register-form', require('./components/login/register-form.vue').default);
Vue.component('loading', require('./components/loading/loading.vue').default);
Vue.component('github-callback', require('./components/auth-callback/auth-callback.vue').default);

new Vue({
    el: '#app',
    router: router,
    store: store
});
