<template>
    <nav class="menu-container">
        <router-link class="menu-title" to="/">Project</router-link>
        <div class="menu-operation">
            <router-link class="menu-login" to="/login" v-if="!user.authentication">
                <button class="btn menu-button">登陆</button>
            </router-link>
            <router-link class="menu-login" to="/register" v-if="!user.authentication">
                <button class="btn menu-button">注册</button>
            </router-link>
            <router-link class="menu-login" to="" v-if="user.authentication">
                <button class="btn menu-button">管理</button>
            </router-link>
            <div class="menu-login" v-if="user.authentication">
                <button class="btn menu-button" @click="logout">退出</button>
            </div>
        </div>
    </nav>

</template>

<script>
    import {mapState} from 'vuex'
    import Cookie from 'js-cookie'

    export default {
        name: "top-menu",
        computed: {
            ...mapState({
                user: state => state.AuthUser
            }),

        },
        methods: {
            logout() {
                this.$store.dispatch('logoutRequest').then(res => {
                    this.$router.push({name: 'home'})
                })
            }
        }
    }
</script>

<style scoped src="./top-menu.css">

</style>
