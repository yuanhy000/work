<template>
    <div class="notification-container">
        <div class="inside-container">
            <div class="title-container">
                好友验证消息
            </div>
            <div class="content-container">
                <div class="user-container">
                    <img :src="notification.request_user.user_avatar" alt="" class="user-avatar">
                    <div class="user-name">{{notification.request_user.user_name}}</div>
                </div>
                <div class="inside-content">
                    <div class="content-text">
                        {{notification.notification_content}}
                    </div>
                    <div class="content-time">
                        {{notification.request_time}}
                    </div>
                </div>
            </div>
            <div class="operation-container">
                <button class="btn operation-button" @click="consentOperation"
                        v-show="!notification.notification_operation">同意添加
                </button>
                <button class="btn operation-button" @click="refuseOperation"
                        v-show="!notification.notification_operation">拒绝添加
                </button>
                <button class="btn operation-button disabled-button"
                        v-show="notification.notification_operation === 1">已添加
                </button>
                <button class="btn operation-button disabled-button"
                        v-show="notification.notification_operation === 0">已拒绝
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "notification-detail",
        data() {
            return {
                notification: {},
            }
        },
        created() {
            this.notification = this.$route.params.notification;
        },
        methods: {
            consentOperation() {
                let data = {
                    operation: 1,
                    notification_id: this.notification.notification_id,
                    request_user: this.notification.request_user
                };
                axios.post('/api/friends/add/callback', data).then(res => {
                    console.log(res);
                })
            },
            refuseOperation() {

            }
        }
    }
</script>

<style scoped src="./notification-detail.css">

</style>
