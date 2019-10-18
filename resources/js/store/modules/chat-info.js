export default {
    state: {
        chatId: -1,
        chatInfo: {}
    },
    mutations: {
        SET_CHAT_ID(state, payload) {
            state.chatId = payload.chatId;
        },
    },
    actions: {
        setChatId({commit}, chatId) {
            commit({
                type: 'SET_CHAT_ID',
                chatId: chatId
            })
        },

    }
}
