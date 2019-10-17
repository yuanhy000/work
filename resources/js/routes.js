import VueRouter from 'vue-router'
import jwt from './helpers/jwt'
import Store from './store/index'

const routes = [
    {
        path: '/',
        name: 'chat',
        components: require('./components/chat-list/chat-list.vue'),children: [
            {
                path: '',
                name: 'chat',
                components: require('./components/friend-list/default-background'),
            },
            {
                path: '/chat-detail',
                name: 'chat-detail',
                components: require('./components/chat-detail/chat-detail.vue'),
            },
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
        path: '/user-manage',
        components: require('./components/user-manage/manage-wrapper.vue'),
        children: [
            // {
            //     path: '',
            //     name: 'user-info',
            //     components: require('./components/user-info/user-info.vue'),
            // },
            {
                path: '/user-info',
                name: 'user-info',
                components: require('./components/user-info/user-info.vue'),
            },
            {
                path: '/user-account',
                name: 'user-account',
                components: require('./components/user-account/user-account.vue'),
            },
            {
                path: '/user-notification',
                name: 'user-notification',
                components: require('./components/user-notification/user-notification.vue'),
            },
            {
                path: '/notification-detail',
                name: 'notification-detail',
                components: require('./components/user-notification/notification-detail.vue'),
            },
        ],
        meta: {requiresAuth: true}
    },
    {
        path: '/friend-list',
        components: require('./components/friend-list/friend-list.vue'),
        children: [
            {
                path: '',
                name: 'friend-list',
                components: require('./components/friend-list/default-background'),
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

const originalPush = VueRouter.prototype.push;
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
};
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
