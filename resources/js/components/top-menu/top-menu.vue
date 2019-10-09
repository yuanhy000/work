<template>
    <div>
        <nav class="menu-container">
            <router-link class="menu-title" to="/">Project</router-link>
            <div class="navigation-container">
                <img src="./../../../image/chat-on.svg" alt="" class="navigation-img" v-if="selectIndex === 0"
                     @click="navigateChat">
                <img src="./../../../image/chat.svg" alt="" class="navigation-img" v-else @click="navigateChat">
                <img src="./../../../image/user-on.svg" alt="" class="navigation-img" v-if="selectIndex === 1"
                     @click="navigateFriend">
                <img src="./../../../image/user.svg" alt="" class="navigation-img" v-else @click="navigateFriend">
            </div>
            <div class="menu-operation">
                <router-link class="menu-login" to="/login" v-if="!user.authentication">
                    <button class="btn menu-button">登陆</button>
                </router-link>
                <router-link class="menu-login" to="/register" v-if="!user.authentication">
                    <button class="btn menu-button">注册</button>
                </router-link>
                <!--            <router-link class="menu-login" to="" v-if="user.authentication">-->
                <!--                <button class="btn menu-button">管理</button>-->
                <!--            </router-link>-->

                <notification :user-info="user" v-if="user.authentication" @leave="navigateNotification">
                </notification>
                <div class="menu-manage" v-if="user.authentication" @click="showList" ref="menuUser">
                    <img :src="user.user_avatar" alt="" class="menu-avatar">
                    <div class="menu-name">{{user.user_name}}</div>
                    <img src="./../../../image/more.svg" alt="" class="menu-icon">
                </div>
            </div>
        </nav>
        <transition name="fade" mode="out-in">
            <!--        <el-collapse-transition>-->
            <div class="manage-list" ref="menu" v-show="manageDisplay"
                 :style="{marginRight:this.marginRight+'px'}">
                <div class="item-top" @click="manageUser">个人中心</div>
                <div class="item-bottom" @click="logout">退出</div>
            </div>
            <!--        </el-collapse-transition>-->
        </transition>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import notification from "./notification";


    export default {
        name: "top-menu",
        components: {
            notification
        },
        computed: {
            ...mapState({
                user: state => state.AuthUser
            }),
        },
        data() {
            return {
                manageDisplay: false,
                marginRight: 0,
                selectIndex: 0
            }
        },
        mounted: function () {
            document.addEventListener("click", this.clickEvent);
            let router = this.$route.path;
            switch (router) {
                case '/':
                    this.selectIndex = 0;
                    break;
                case '/friend-list/':
                    this.selectIndex = 1;
                    break;
                case '/search-user':
                    this.selectIndex = 1;
                    break;
            }
        },
        beforeDestroy() {
            document.removeEventListener("click", this.clickEvent);
        },
        methods: {
            navigateChat() {
                this.selectIndex = 0;
                this.$router.push({name: 'home'});
            },
            navigateFriend() {
                this.selectIndex = 1;
                this.$router.push({name: 'friend-list'});
            },
            navigateNotification() {
                this.selectIndex = 2;
            },
            logout() {
                this.manageDisplay = false;
                this.$store.dispatch('logoutRequest').then(res => {
                    this.$router.push({name: 'home'})
                })
            },
            showList() {
                this.marginRight = document.body.clientWidth - this.$refs.menuUser.getBoundingClientRect().right;
                this.manageDisplay = !this.manageDisplay;
            },
            manageUser() {
                this.manageDisplay = false;
                this.selectIndex = 3;
                this.$router.push({name: 'user-info'});
            },
            clickEvent(e) {
                if (!this.$refs.menu.contains(e.target) && !this.$refs.menuUser.contains(e.target)) {
                    this.manageDisplay = false;
                }
            }
        }
    }
</script>

<style scoped src="./top-menu.css">

</style>
