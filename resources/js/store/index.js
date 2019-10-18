import Vue from 'vue'
import Vuex from 'vuex'

import AuthUser from './modules/auth-user'
import Login from './modules/login'
import Notification from './modules/notification'
import SearchStatus from './modules/search'
import ChatINfo from './modules/chat-info'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        AuthUser,
        Login,
        Notification,
        SearchStatus,
        ChatINfo
    },
    strict: true
})
