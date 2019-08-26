<template>
    <div class="search-user-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="search-container">
            <input type="text" class="search-input" v-model="inputContent"
                   placeholder="昵称 / 邮箱 / 手机号码">
            <button class="btn search-button" @click="searchUser">搜索</button>
        </div>
        <div class="search-result">
            <img src="./../../../image/search-default2.png" alt="" class="search-default-img"
                 v-show="!searchResult">
            <div v-for="user in this.users" class="search-item" v-show="searchResult">
                <h4>{{ user.user_name }}</h4>
                <p>{{ user.user_email }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import loading from './../loading/loading';

    export default {
        name: "search-user",
        components: {
            loading: loading
        },
        data() {
            return {
                inputContent: null,
                loading: false,
                searchResult: false,
                users: null
            }
        },
        methods: {
            searchUser() {
                if (this.inputContent) {
                    this.loading = true;
                    let data = {
                        content: this.inputContent
                    };
                    axios.post('api/search/user', data).then(res => {
                        this.loading = false;
                        this.searchResult = true;
                        console.log(res);
                        this.users = res.data.data.users;
                        console.log(this.users)
                    })
                }
            },
        }
    }
</script>

<style scoped src="./addition.css">

</style>
