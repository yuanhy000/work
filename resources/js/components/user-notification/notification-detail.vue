<template>
    <div class="notification-container">
        <div class="inside-container">
            <div class="title-container">
                好友验证消息
            </div>
            <div class="content-container">
                <div class="user-container">
                    <img :src="notification.request_user.user_avatar" alt="" class="user-avatar"
                         @click="navigateUser">
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
                <button class="btn operation-button" @click="friendCallback('consent')"
                        v-show="showOperationButton">同意添加
                </button>
                <button class="btn operation-button" @click="friendCallback('refuse')"
                        v-show="showOperationButton">拒绝添加
                </button>
                <button class="btn operation-button" @click="addFriendAgain"
                        v-show="addAgainButton">再次申请
                </button>
                <!--                <button class="btn operation-button"-->
                <!--                        v-show="sendMessageButton">发送消息-->
                <!--                </button>-->
                <button class="btn operation-button disabled-button"
                        v-show="successAddButton">已添加
                </button>
                <button class="btn operation-button disabled-button"
                        v-show="refuseAddButton">已拒绝
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
                isFriend: false
            }
        },
        computed: {
            showOperationButton: function () {
                return this.notification.notification_type === 'add-friend'
                    && this.notification.notification_operation === null;
            },
            addAgainButton: function () {
                return this.notification.notification_type === 'friend-callback'
                    && this.notification.notification_operation === 0 && !this.isFriend;
            },
            sendMessageButton: function () {
                // return this.notification.notification_type === 'friend-callback'
                //     && this.notification.notification_operation === 1;
                return this.isFriend;
            },
            successAddButton: function () {
                return this.notification.notification_type === 'add-friend'
                    && this.notification.notification_operation === 1;
            },
            refuseAddButton: function () {
                return this.notification.notification_type === 'add-friend'
                    && this.notification.notification_operation === 0;
            },
        },
        created() {
            this.notification = this.$route.params.notification;
            axios.post('api/users/is_friend', this.notification.request_user.user_id).then(res => {
                this.isFriend = res.data.isFriend;
            })
        },
        methods: {
            navigateUser() {
                this.$router.push({name: 'friend-info', params: {user: this.notification.request_user}});
            },
            friendCallback(type) {
                let data = {
                    notification_id: this.notification.notification_id,
                    request_user: this.notification.request_user
                };
                if (type === 'consent') {
                    data.operation = 1;
                } else {
                    data.operation = 0;
                }
                axios.post('/api/friends/add/callback', data).then(res => {
                    if (type === 'consent') {
                        this.$message({
                            message: '好友添加成功',
                            type: 'success'
                        });
                        this.isFriend = true;
                        this.notification.notification_operation = 1;
                    } else {
                        this.notification.notification_operation = 0;
                    }
                })
            },
            addFriendAgain() {
                axios.post('api/friends/add', this.notification.request_user.user_id).then(res => {
                    this.$message({
                        message: '好友申请已发送',
                        type: 'success'
                    });
                })
            }
        }
    }
</script>

<style scoped src="./notification-detail.css">

</style>
