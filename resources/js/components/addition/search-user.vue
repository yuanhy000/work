<template>
    <div class="search-user-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="search-container">
            <input type="text" class="search-input" v-model="inputContent"
                   placeholder="昵称 / 邮箱 / 手机号码" @keyup.enter="searchUser">
            <button class="btn search-button" @click="searchUser">搜索</button>
        </div>
        <div class="search-content">
            <div class="pre-img-container" v-if="searchResult && this.page_index>1" @click="prePage">
                <img src="./../../../image/pre.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>
            <transition name="fade">
                <div :class="this.searchResult ? 'search-result-over' : 'search-result-default'"
                     v-if="show_content">
                    <img src="./../../../image/search-default2.png" alt="" class="search-default-img"
                         v-show="!searchResult">
                    <router-link v-for="user in this.users.slice((this.page_index - 1) * 16,this.page_index * 16)"
                                 class="search-item" v-show="searchResult" v-bind:key="user.id" to="/">
                        <img :src="user.user_avatar" alt="" class="item-avatar">
                        <div class="item-info">
                            <div class="item-name-container">
                                <div class="item-name">{{user.user_name}}</div>
                                <div class="item-name">({{user.user_number}})</div>
                            </div>
                            <div class="item-email">{{user.user_email}}</div>
                        </div>
                    </router-link>
                </div>
            </transition>
            <div class="pre-img-container" v-if="searchResult && this.page_index<this.last_page"
                 @click="nextPage">
                <img src="./../../../image/next.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>
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
                searchData: null,
                loading: false,
                searchResult: false,
                users: [],
                page_index: 1,
                load_page: 1,
                last_page: null,
                links: {},
                show_content: true
            }
        },
        methods: {
            searchUser() {
                if (this.inputContent) {
                    this.loading = true;
                    let data = {
                        content: this.inputContent
                    };
                    this.searchData = data;
                    axios.post('api/search/user', data).then(res => {
                        this.users = res.data.data.users;
                        console.log(res);
                        this.loading = false;
                        this.searchResult = true;
                        this.last_page = res.data.meta.last_page;
                        this.links = res.data.links;
                        console.log(this.users)
                    })
                }
            },
            nextPage() {
                this.show_content = !this.show_content;
                if (this.page_index < this.load_page) {
                    this.page_index++;
                    return
                }
                this.loading = true;
                console.log(this.searchData)
                axios.post(this.links.next, this.searchData).then(res => {
                    this.loading = false;
                    this.page_index++;
                    this.load_page++;
                    console.log(res);
                    this.links = res.data.links;
                    this.users.push.apply(this.users, res.data.data.users);
                    console.log(this.users);
                })
            },
            prePage() {
                this.page_index--;
            }
        }
    }
</script>

<style scoped src="./addition.css">

</style>
