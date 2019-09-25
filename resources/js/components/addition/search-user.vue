<template>
    <div class="search-user-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="search-container">
            <input type="text" class="search-input" v-model="inputContent"
                   placeholder="昵称 / 邮箱 / 手机号码" @keyup.enter="searchUser">
            <button class="btn search-button" @click="searchUser">搜索</button>
        </div>
        <div class="search-content">
            <div class="pre-img-container" v-if="searchResult && this.page_index>1 && show_content"
                 @click="prePage">
                <img src="./../../../image/pre.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>
            <transition name="fade">
                <div :class="this.searchResult ? 'search-result-over' : 'search-result-default'"
                     v-if="show_content">
                    <img src="./../../../image/search-default2.png" alt="" class="search-default-img"
                         v-show="!searchResult">
                    <div v-for="user in this.users.slice((this.page_index - 1) * 16,this.page_index * 16)"
                         class="search-item" v-show="searchResult" v-bind:key="user.id"
                         @click="navigateUser(user.user_id)">
                        <img :src="user.user_avatar" alt="" class="item-avatar">
                        <div class="item-info">
                            <div class="item-name-container">
                                <div class="item-name">{{user.user_name}}</div>
                                <div class="item-name">({{user.user_number}})</div>
                            </div>
                            <div class="item-email">{{user.user_email}}</div>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="pre-img-container" v-if="searchResult && this.page_index<this.last_page && show_content"
                 @click="nextPage">
                <img src="./../../../image/next.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>
        </div>
    </div>
</template>

<script>
    import loading from './../loading/loading';
    import notification from "../notification/notification";

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
                show_content: true,
                notification: false
            }
        },
        methods: {
            searchUser() {
                if (this.inputContent) {
                    let searchContent = this.operationBeforeRequest();
                    this.$store.dispatch('searchUser', searchContent).then(res => {
                        this.setUsers(res);
                    }).catch(error => {
                        if (error.response.status === 500 && this.$store.state.SearchStatus.searchSuccess) {
                            this.$store.dispatch('searchFailed');
                            this.$store.dispatch('searchUser', searchContent).then(res => {
                                this.setUsers(res);
                            }).catch(error => {
                                this.searchFailed();
                            })
                        }
                    })
                }
            },
            nextPage() {
                if (this.page_index < this.load_page) {
                    this.show_content = false;
                    this.page_index++;
                    this.setLoading();
                    return
                }
                this.operationBeforeRequest();
                this.$store.dispatch('searchNextPage', this.links.next, this.searchData).then(res => {
                    this.setNextInfo(res);
                }).catch(error => {
                    if (error.response.status === 500 && this.$store.state.SearchStatus.searchSuccess) {
                        this.$store.dispatch('searchFailed');
                        this.$store.dispatch('searchNextPage', this.links.next, this.searchData).then(res => {
                            this.setNextInfo(res);
                        }).catch(error => {
                            this.searchFailed();
                        })
                    }
                })
            },
            prePage() {
                this.page_index--;
                this.show_content = false;
                this.setLoading();
            },
            setUsers(res) {
                this.users = res.data.data.users;
                this.last_page = res.data.meta.last_page;
                this.setSearchInfo(res)
            },
            setSearchInfo(res) {
                this.links = res.data.links;
                this.searchResult = true;
                this.loading = false;
                this.show_content = true;
            },
            operationBeforeRequest() {
                let data = {
                    content: this.inputContent
                };
                this.searchData = data;
                this.show_content = false;
                this.loading = true;
                this.notification = false;
                this.$store.dispatch('searchSuccess');
                return data;
            },
            setLoading() {
                setTimeout(() => {
                    this.show_content = true;
                }, 500);
            },
            searchFailed() {
                this.$message({
                    message: '网络不稳定，请稍后再试',
                    type: 'error'
                });
                this.notification = true;
                this.searchResult = false;
                this.show_content = true;
                this.loading = false;
            },
            setNextInfo(res) {
                this.page_index++;
                this.load_page++;
                this.users.push.apply(this.users, res.data.data.users);
                this.setSearchInfo(res);
            },
            navigateUser(userId) {
                let user = this.users.find(
                    (item) => {
                        return item.user_id === userId
                    }
                );
                this.$router.push({name: 'friend-info', params: {user: user}});
            }
        }
    }
</script>

<style scoped src="./addition.css">

</style>
