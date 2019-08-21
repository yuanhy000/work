export default {
    state: {
        authentication: false,
        username: null,
        email: null,
        phone: null
    },

    mutations: {
        SET_AUTH_USER(state, payload) {
            state.authentication = true;
            state.username = payload.user.name;
            state.email = payload.user.email;
            state.phone = payload.user.phone;
        },

        INIT_AUTH_USER(state) {
            state.authentication = false;
            state.username = null;
            state.email = null;
            state.phone = null;
        }
    },

    actions: {
        setAuthUser({commit, dispatch}) {
            return axios.get('/api/user').then(res => {
                commit({
                    type: 'SET_AUTH_USER',
                    user: res.data
                })
            }).catch(error => {
                dispatch('refreshToken');
            })
        },

        initAuthUser({commit}) {
            commit({
                type: 'INIT_AUTH_USER',
            })
        },

        refreshToken({commit, dispatch}) {
            return axios.post('/api/token/refresh').then(res => {
                dispatch('loginSuccess', res.data);
            }).catch(error => {
                dispatch('logoutRequest');
            })
        }
    }
}
