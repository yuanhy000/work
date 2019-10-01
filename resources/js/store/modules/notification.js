export default {
    state: {
        number: 0,
        hasNotification: false
    },
    mutations: {
        SET_NUMBER(state, payload) {
            state.number = payload.number;
        },
        SET_STATUS(state, payload) {
            state.hasNotification = payload.status;
        },
        INCREMENT_NUMBER(state) {
            state.number += 1;
        },
        DECREMENT_NUMBER(state) {
            state.number -= 1;
        },
        INIT_NOTIFICATION(state) {
            state.level = 0;
            state.msg = false;
        }
    },
    actions: {
        setNumber({commit}, number) {
            commit({
                type: 'SET_NUMBER',
                number: number
            })
        },
        setStatus({commit}, status) {
            commit({
                type: 'SET_STATUS',
                status: status
            })
        },
        incrementNumber({commit}) {
            commit({
                type: 'INCREMENT_NUMBER'
            })
        },
        decrementNumber({commit}) {
            commit({
                type: 'DECREMENT_NUMBER'
            })
        },
        initNotification({commit}) {
            commit({
                type: 'INIT_NOTIFICATION'
            })
        },

    }
}
