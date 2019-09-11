<template>
    <div>
        <nav class="menu-container">
            <router-link class="menu-title" to="/">Project</router-link>
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
                <div class="menu-manage" v-if="user.authentication" @click="showList" ref="menuUser">
                    <img :src="user.user_avatar" alt="" class="menu-avatar">
                    <div class="menu-name">{{user.user_name}}</div>
                    <img src="./../../../image/more.svg" alt="" class="menu-icon">
                </div>
                <!--            <div class="menu-login" v-if="user.authentication">-->
                <!--                <button class="btn menu-button" @click="logout">退出</button>-->
                <!--            </div>-->
            </div>
        </nav>
        <transition name="fade" mode="out-in">
            <div class="manage-list" ref="menu" v-show="manageDisplay"
                 :style="{marginRight:this.marginRight+'px'}">
                <div class="item-top" @click="manageUser">个人中心</div>
                <div class="item-bottom">退出</div>
            </div>
        </transition>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "top-menu",
        computed: {
            ...mapState({
                user: state => state.AuthUser
            }),
        },
        data() {
            return {
                manageDisplay: false,
                marginRight: 0,
            }
        },
        mounted: function () {
            document.addEventListener("click", this.clickEvent);
        },
        beforeDestroy() {
            document.removeEventListener("click", this.clickEvent);
        },
        methods: {
            logout() {
                this.$store.dispatch('logoutRequest').then(res => {
                    this.$router.push({name: 'home'})
                })
            },
            showList() {
                this.marginRight = document.body.clientWidth - this.$refs.menuUser.getBoundingClientRect().right;
                this.manageDisplay = !this.manageDisplay;
            },
            manageUser() {
                this.$router.push({name: 'user-manage'});
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
