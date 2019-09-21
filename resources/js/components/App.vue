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
        mounted() {
            if (jwt.getToken()) {
                this.$store.dispatch('setAuthUser');
            } else if (Cookie.get('auth_id')) {
                this.$store.dispatch('refreshToken')
            }
        }
    }
</script>
<style>
    .btn:focus,
    .btn:active:focus,
    .btn.active:focus,
    .btn.focus,
    .btn:active.focus,
    .btn.active.focus {
        outline: none;
        box-shadow: none;
    }

    .distpicker-address-wrapper select {

        border: 2px solid #DBE2EC;
        outline: none;

    }

    .verify-move-block {
        background-color: #8EA7C7 !important;
        border-radius: 4px !important;
    }
    .verify-move-block:hover {
        background-color: #788FAF !important;
        transition-duration: 0.3s;
    }

    .icon-right:before {
        background-image: url("./../../image/right.svg");
        width: 25px;
        height: 25px;
    }

    .icon-check:before {

        background-image: url("./../../image/correct.svg");
        width: 25px;
        height: 25px;
    }
    .icon-close:before {

        background-image: url("./../../image/false.svg");
        width: 25px;
        height: 25px;
    }

    .verify-left-bar {
        border-radius: 4px !important;
        border-color: #8EA7C7 !important;
    }

    .verify-msg {
        color: #777;
    }

    .fade-enter-active {
        transition: all 0.2s;
        /*transition-delay: 0.8s;*/
    }

    .fade-leave-active {
        transition: all 0.2s;
    }

    .fade-enter, .fade-leave-active {
        opacity: 0;
    }
</style>
