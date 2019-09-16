export default {
    state: {
        authentication: false,
        user_name: null,
        user_number: null,
        user_email: null,
        user_phone: null,
        user_avatar: null,
        user_age: null,
        user_sex: null,
        user_signature: null,
        user_birth: null,
        user_blood_type: null,
        user_address: null,
        user_hometown: null,
        user_school: null,
        user_constellation: null,
        user_zodiac: null,
    },

    mutations: {

        SET_AUTH_USER(state, payload) {
            for (let item in state) {
                state[item] = payload.user.data[item];
            }
            state.authentication = true;
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

        updateUser({commit, dispatch}, userInfo) {
            console.log(userInfo);
            return axios.post('/api/users/update', userInfo).then(res => {
                console.log(res.data);
                commit({
                    type: 'SET_AUTH_USER',
                    user: res.data
                })
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
        },
    }
}
