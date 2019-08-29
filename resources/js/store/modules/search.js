export default {
    state: {
        searchSuccess: true,
    },
    mutations: {
        SET_SEARCH_STATUS(state, payload) {
            state.searchSuccess = payload.status;
        },
    },
    actions: {
        searchUser({commit, dispatch}, searchContent) {
            return axios.post('/api/users/search', searchContent).then(res => {
                dispatch('searchSuccess');
                return res;
            })
        },
        searchNextPage({commit, dispatch}, links, searchContent) {
            return axios.post(links, searchContent).then(res => {
                dispatch('searchSuccess');
                return res;
            });
        },
        searchFailed({commit, dispatch}) {
            commit({
                type: 'SET_SEARCH_STATUS',
                status: false
            })
        },
        searchSuccess({commit, dispatch}) {
            commit({
                type: 'SET_SEARCH_STATUS',
                status: true
            })
        }


    }
}
