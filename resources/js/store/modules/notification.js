export default {
    state: {
        level: 'success',
        msg: null
    },
    mutations: {
        SHOW_NOTIFICATION(state, payload) {
            state.level = payload.notification.level;
            state.msg = payload.notification.msg;
        },
        HIDE_NOTIFICATION(state, payload) {
            state.level = 'success';
            state.msg = null;
        }
    },
    actions: {
        showNotification({commit}, notification) {
            commit({
                type: 'SHOW_NOTIFICATION',
                notification: notification
            })
        },
        hideNotification({commit}) {
            commit({
                type: 'HIDE_NOTIFICATION'
            })
        },

    }
}
