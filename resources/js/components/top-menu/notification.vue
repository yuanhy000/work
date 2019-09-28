<template>
    <div class="notification-container" :class="this.hasNotification||this.number ? 'glint' : ''"
         @click="navigateNotification">
        <img src="./../../../image/notification.svg" alt="" class="notification-img">
        <div class="notification-number">{{number}}</div>
    </div>
</template>

<script>
    export default {
        name: "notification",
        props: {
            userInfo: {}
        },
        data() {
            return {
                number: 0,
                hasNotification: false
            }
        },
        mounted() {
            axios.post('api/notifications/unread').then(res => {
                let notifications = res.data.data;
                this.number = notifications.unread_number;
            });
            setTimeout(res => {
                console.log('Friend.accept.' + this.userInfo.user_id);
                window.Echo.private('Friend.accept.' + this.userInfo.user_id)
                    .listen('AddFriend', (e) => {
                        this.hasNotification = true;
                        console.log(e);
                        this.number++;
                    });
            }, 1000)
        },
        methods: {
            navigateNotification() {
                this.$router.push({name:'user-notification'})
            }
        }
    }
</script>

<style scoped src="./top-menu.css">

</style>
