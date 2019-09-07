import VueRouter from 'vue-router'
import jwt from './helpers/jwt'
import Store from './store/index'

const routes = [
    {
        path: '/',
        name: 'home',
        components: require('./components/home/home.vue'),
        meta: {requiresAuth: true}
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
                // meta: {requiresAuth: true}
            },
        ],
        meta: {requiresGuest: true}
    },
    {
        path: '/user-manage',
        name: 'user-manage',
        components: require('./components/user-manage/manage-wrapper.vue'),
        children: [
        ],
        meta: {requiresAuth: true}
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
                // meta: {requiresAuth: true}
            },
        ],
        meta: {requiresGuest: true}
    },
    {
        path: '/register',
        name: 'register',
        components: require('./components/login/register.vue'),
    },
    {
        path: '/auth-callback',
        name: 'auth-callback',
        components: require('./components/auth-callback/auth-callback.vue'),
    },
    {
        path: '/friend-list',
        components: require('./components/friend-list/friend-list.vue'),
        children: [
            {
                path: '',
                name: 'friend-list',
                // components: require('./components/login/login-email-form')
            },
            {
                path: '/addition',
                name: 'addition',
                components: require('./components/addition/addition'),
                children: [
                    {
                        path: '/search-user',
                        name: 'search-user',
                        components: require('./components/addition/search-user')
                    },
                ]
            },
            {
                path: '/friend-info',
                name: 'friend-info',
                components: require('./components/friend-info/friend-info'),
                meta: {requiresAuth: true}
            },
        ],
        meta: {requiresAuth: true}
    },
];

const router = new VueRouter({
    // mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        if (Store.state.AuthUser.authentication || jwt.getToken()) {
            return next();
        } else {
            return next({'name': 'email-login'});
        }
    }
    if (to.meta.requiresGuest) {
        if (Store.state.AuthUser.authentication || jwt.getToken()) {
            return next({'name': 'home'});
        } else {
            return next();
        }
    }
    next();
});


// router.beforeEach((to, from, next) => {
//     const token = localStorage.getItem("token")
//     if (to.matched.some(record => record.meta.requireAuth || record.meta.homePages)) {
// //路由元信息requireAuth:true，或者homePages:true，则不做登录校验
//         next()
//     } else {
//         if (token) {//判断用户是否登录
//             if (Object.keys(from.query).length === 0) {//判断路由来源是否有query，处理不是目的跳转的情况
//                 next()
//             } else {
//                 let redirect = from.query.redirect//如果来源路由有query
//                 if (to.path === redirect) {//这行是解决next无限循环的问题
//                     next()
//                 } else {
//                     next({path: redirect})//跳转到目的路由
//                 }
//             }
//         } else {
//             if (to.path === "/login") {
//                 next()
//             } else {
//                 next({
//                     path: "/login",
//                     query: {redirect: to.fullPath}//将目的路由地址存入login的query中
//                 })
//             }
//         }
//     }
//     return
// })

export default router
