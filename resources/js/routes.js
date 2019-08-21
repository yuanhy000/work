import VueRouter from 'vue-router'

let routes = [
    {
        path: '/',
        name: 'home',
        components: require('./components/home/home.vue')
    },
    {
        path: '/login',
        // name: 'login',
        components: require('./components/login/login.vue'),
        children: [
            {
                path: '',
                name: 'email-login',
                components: require('./components/login/login-email-form')
            },
            {
                path: '/login/phone',
                name: 'phone-login',
                components: require('./components/login/login-phone-form'),
                meta: {requiresAuth: true}
            },
        ]
    },
    {
        path: '/register',
        name: 'register',
        components: require('./components/login/register.vue'),
    }
];


export default new VueRouter({
    routes
})
