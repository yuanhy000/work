<template>
    <div class="notification-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <toast v-show="show_delete" style="z-index:999;" @option="deleteAction"
               :title="toast_title" :content="toast_content"></toast>
        <div class="inside-container">
            <div class="pre-img-container" @click="prePage" v-if="this.page_index>1 && show_content">
                <img src="./../../../image/pre.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>

            <transition name="fade">
                <div class="notification-content" v-if="show_content">
                    <div class="item-out-container"
                         v-for="(notification,index) in notifications.slice((this.page_index - 1) * 5,this.page_index * 5)">
                        <div class="item-time">{{notification.request_time}}</div>
                        <div class="item-container">
                            <div class="item-info">
                                <img v-if="notification.request_user" alt="" class="item-avatar"
                                     :src="notification.request_user.user_avatar">
                                <div class="content-container"
                                     @click="navigateDetail(notification.notification_id)">
                                    <div class="item-content">{{notification.notification_content}}</div>
                                </div>
                            </div>
                            <div class="item-operation">
                                <p>{{notification.status}}</p>
                                <img src="./../../../image/read.svg" alt="" class="item-read-img"
                                     v-if="notification.notification_status">
                                <img src="./../../../image/unread.svg" alt="" class="item-read-img"
                                     v-else>
                                <img src="./../../../image/delete.svg" alt="" class="item-delete-img"
                                     @click="deleteNotification(notification.notification_id,index)">
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="pre-img-container" v-if="this.page_index<this.last_page && show_content"
                 @click="nextPage">
                <img src="./../../../image/next.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>
        </div>
    </div>
</template>

<script>

    import loading from './../loading/loading';
    import toast from "../toast/toast";

    export default {
        name: "user-notification",
        components: {
            loading,
            toast
        },
        data() {
            return {
                notifications: [],
                page_index: 1,
                load_page: 1,
                last_page: null,
                links: {},
                show_content: true,
                loading: false,
                show_delete: false,
                delete_notification: {},
                toast_title: '',
                toast_content: '',
                delete_index: 0
            }
        },
        mounted() {
            axios.post('api/notifications/all').then(res => {
                this.setNotifications(res);
            })
        },
        methods: {
            navigateDetail(notification_id) {
                let notification = this.notifications.find(
                    (item) => {
                        return item.notification_id === notification_id
                    }
                );
                if (notification.notification_status === 0) {
                    axios.post('/api/notifications/read', notification_id).then(res=>{
                        this.$store.dispatch('decrementNumber');
                    });
                }
                this.$router.push({name: 'notification-detail', params: {notification: notification}});
            },
            deleteNotification(notification_id, index) {
                this.delete_notification = this.notifications.find(
                    (item) => {
                        return item.notification_id === notification_id
                    }
                );
                this.delete_index = index;
                this.toast_title = '确认删除';
                this.toast_content = '确认删除用户: ' + this.delete_notification.request_user.user_name +
                    ' 发送给您的消息通知吗?';
                this.show_delete = true;
            },
            deleteAction(option) {
                this.show_delete = false;
                if (option) {
                    let data = {
                        current_page: this.page_index,
                        delete_notification: this.delete_notification
                    };
                    axios.post('api/notifications/delete', data).then(res => {
                        this.load_page = this.page_index;
                        this.notifications.splice((this.page_index - 1) * 5, this.notifications.length);
                        this.notifications.push.apply(this.notifications, res.data.data.notifications);
                        //重新拼接请求下一页的链接
                        this.links.next = this.links.last.substr(0, this.links.last.length - 1) + (this.page_index + 1)
                        if (this.delete_notification.notification_status === 0) {
                            this.$store.dispatch('decrementNumber')
                        }
                    });
                }
            },
            setNotifications(res) {
                this.notifications = res.data.data.notifications;
                this.last_page = res.data.meta.last_page;
                this.setLinkInfo(res)
            },
            setLinkInfo(res) {
                this.links = res.data.links;
                setTimeout(res => {
                    this.loading = false;
                    this.show_content = true;
                }, 400)
            },
            prePage() {
                this.page_index--;
                this.show_content = false;
                this.setLoading();
            },
            nextPage() {
                if (this.page_index < this.load_page) {
                    this.show_content = false;
                    this.page_index++;
                    this.setLoading();
                    return
                }
                this.operationBeforeRequest();
                axios.post(this.links.next).then(res => {
                    console.log(res);
                    this.setNextInfo(res);
                }).catch(error => {
                    this.$message({
                        message: '网络不稳定，请稍后再试',
                        type: 'error'
                    });
                })
            },
            setNextInfo(res) {
                this.page_index++;
                this.load_page++;
                this.notifications.push.apply(this.notifications, res.data.data.notifications);
                this.setLinkInfo(res);
            },
            setLoading() {
                setTimeout(() => {
                    this.show_content = true;
                }, 400);
            },
            operationBeforeRequest() {
                this.show_content = false;
                this.loading = true;
            },
        }
    }
</script>

<style scoped src="./user-notification.css">

</style>
