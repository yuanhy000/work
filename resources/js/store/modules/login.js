import jwtToken from './../../helpers/jwt'

export default {
    actions: {
        registerRequest({dispatch}, registerInfo) {
            return axios.post('/api/register', registerInfo).then(res => {
                dispatch('loginSuccess', res.data);
            })
        },

        loginRequest({dispatch}, loginInfo) {
            return axios.post('/api/login', loginInfo).then(res => {
                dispatch('loginSuccess', res.data);
            })
        },

        loginSuccess({dispatch}, tokenResponse) {
            jwtToken.setToken(tokenResponse.access_token);
            dispatch('setAuthUser');
        },

        logoutRequest({dispatch}) {
            return axios.post('/api/logout').then(res => {
                jwtToken.removeToken();
                dispatch('initAuthUser');
            })
        }
    }
}
