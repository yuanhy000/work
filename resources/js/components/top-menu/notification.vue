<template>
    <div :class="notifications.number ? 'glint' : ''"
         class="notification-container" @click="navigateNotification">
        <img src="./../../../image/notification.svg" alt="" class="notification-img">
        <div class="notification-number">{{notifications.number}}</div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "notification",
        props: {
            userInfo: {}
        },
        computed: {
            ...mapState({
                notifications: state => state.Notification
            }),
        },
        mounted() {
            axios.post('api/notifications/unread').then(res => {
                let notifications = res.data.data;
                this.$store.dispatch('setNumber', notifications.unread_number);
            });
            setTimeout(() => {
                window.Echo.private('Friend.accept.' + this.userInfo.user_id)
                    .listen('AddFriend', (e) => {
                        this.$store.dispatch('setStatus', true);
                        this.$store.dispatch('incrementNumber');
                    });
                window.Echo.private('Friend.callback.' + this.userInfo.user_id)
                    .listen('FriendCallback', (e) => {
                        this.$store.dispatch('setStatus', true);
                        this.$store.dispatch('incrementNumber');
                    });
            }, 1000)
        },
        methods: {
            navigateNotification() {
                this.$emit('leave', 'true');
                this.$router.push({name: 'user-notification'})
            }
        }
    }
</script>

<style scoped src="./top-menu.css">

</style>
