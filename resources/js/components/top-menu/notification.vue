<template>
    <div class="notification-container">
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
                number: 0
            }
        },
        mounted() {
            setTimeout(res => {
                console.log(this.userInfo)
                console.log('Friend.accept.' + this.userInfo.user_id);
                window.Echo.private('Friend.accept.' + this.userInfo.user_id)
                    .listen('AddFriend', (e) => {
                        console.log(e);
                        console.log('private channel call');
                        this.number++;
                    });
            }, 500)
        }
    }
</script>

<style scoped src="./top-menu.css">

</style>
