<template>
    <div class="notification-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="inside-container">
            <div class="pre-img-container" @click="prePage" v-if="this.page_index>1 && show_content">
                <img src="./../../../image/pre.svg" alt="" class="pre-image">
            </div>
            <div class="perch-div" v-else></div>

            <transition name="fade">
                <div class="notification-content" v-if="show_content">
                    <div class="item-out-container"
                         v-for="notification in notifications.slice((this.page_index - 1) * 5,this.page_index * 5)">
                        <div class="item-time">{{notification.request_time}}</div>
                        <div class="item-container">
                            <div class="item-info">
                                <img v-if="notification.request_user" alt="" class="item-avatar"
                                     :src="notification.request_user.user_avatar">
                                <div class="content-container">
                                    <div class="item-content">{{notification.notification_content}}</div>
                                </div>
                            </div>
                            <div class="item-operation">
                                <img src="./../../../image/read.svg" alt="" class="item-read-img"
                                     v-show="notification.status">
                                <img src="./../../../image/unread.svg" alt="" class="item-read-img"
                                     v-show="!notification.status">
                                <img src="./../../../image/delete.svg" alt="" class="item-delete-img">
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

    export default {
        name: "user-notification",
        components: {
            loading: loading
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
            }
        },
        mounted() {
            axios.post('api/notifications/all').then(res => {
                console.log(res.data.data.notifications);
                this.setNotifications(res);
            })
        },
        methods: {
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
                    // if (error.response.status === 500 && this.$store.state.SearchStatus.searchSuccess) {
                    //     this.$store.dispatch('searchFailed');
                    //     this.$store.dispatch('searchNextPage', this.links.next, this.searchData).then(res => {
                    //         this.setNextInfo(res);
                    //     }).catch(error => {
                    //         this.searchFailed();
                    //     })
                    // }
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
                }, 500);
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
