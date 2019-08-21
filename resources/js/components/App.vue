<template>
    <div>
        <top-menu></top-menu>
        <notification></notification>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    import TopMenu from './top-menu/top-menu'
    import Notification from './notification/notification'
    import jwt from './../helpers/jwt'
    import Cookie from 'js-cookie'

    export default {
        components: {
            TopMenu,
            Notification
        },
        beforeCreate() {
            if (jwt.getToken()) {
                this.$store.dispatch('setAuthUser');
            } else if (Cookie.get('auth_id')) {
                this.$store.dispatch('refreshToken')
            }
        }
    }
</script>
