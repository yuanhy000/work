<template>
    <div class="chat-detail-container">
        <div class="chat-user-container">
            <div class="friend-info">
                <img :src="friendInfo.user_avatar" alt="" class="friend-avatar">
                <div class="friend-name">
                    {{friendName}}
                </div>
            </div>
        </div>
        <div class="chat-content-container">
            <!--            <div class="dialog bubble">123</div>-->
            <!--            <div-scroll color="rgba(0,0,0,0.5)" size="5" class="scroll">-->
            <el-scrollbar style="height:99%;" ref="myScrollbar">
                <div>
                    <transition name="el-fade-in">
                        <div class="loadEffect" v-show="loading">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </transition>

                    <transition name="el-fade-in">
                        <div v-show="!loading">
                            <div class="item-content-container" v-if="chatInfo" v-for="(chat,index) in chatInfo.data">
                                <div class="item-time" v-if="getTimeInterval(index)">
                                    {{chat.created_at}}
                                </div>
                                <div :class="chat.isFriendSend ? 'item-body-left' : 'item-body-right'"
                                     v-if="chat.content">
                                    <img v-if="chat.isFriendSend" class="friend-avatar"
                                         :src="chat.isFriendSend ? friendInfo.user_avatar : userInfo.user_avatar"
                                         alt="">
                                    <div
                                        :class="chat.isFriendSend ? 'bubble-left item-content-left' : 'bubble-right item-content-right'">
                                        {{chat.content}}
                                    </div>
                                    <img v-if="!chat.isFriendSend" alt="" class="friend-avatar"
                                         :src="chat.isFriendSend ? friendInfo.user_avatar : userInfo.user_avatar">
                                </div>
                            </div>
                        </div>

                    </transition>
                </div>
            </el-scrollbar>
            <!--            </div-scroll>-->
        </div>
        <div class="chat-input-container">

        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import loading from "../loading/loading";

    export default {
        name: "chat-detail",
        data() {
            return {
                friendId: 0,
                friendName: '',
                friendInfo: [],
                chatInfo: [],
                loading: false,
                nextPageUrl: '',
                loadPage: 0,
                totalPage: 0,
            }
        },
        components: {
            loading
        },
        computed: {
            ...mapState({
                userInfo: state => state.AuthUser
            }),
        },
        created() {
            this.friendId = this.$route.params.friend_id;
            let that = this;
            axios.post('api/chats/friend', this.friendId).then(res => {
                let requestInfo = res.data.data;
                this.friendInfo = requestInfo.friend_info;
                this.chatInfo = requestInfo.chat_info;
                this.friendName = requestInfo.friend_name;
                this.nextPageUrl = requestInfo.chat_info.next_page_url;
                this.totalPage = requestInfo.chat_info.last_page;
                this.loadPage = 1;

                this.chatInfo.data.reverse();
                for (let item in this.chatInfo.data) {
                    this.chatInfo.data[item].isFriendSend = this.chatInfo.data[item].from_user_id === this.friendInfo.user_id;
                }
                let div = that.$refs['myScrollbar'].$refs['wrap'];
                that.$nextTick(() => {
                    div.scrollTop = div.scrollHeight;
                })
            })
        },
        mounted() {
            document.addEventListener("scroll", this.checkScroll, true);
        },
        beforeDestroy() {
            document.removeEventListener("scroll", this.checkScroll, true);
        },
        methods: {
            checkScroll() {
                // console.log(this.$refs['myScrollbar'].wrap.scrollTop);
                let previousScrollheight = this.$refs['myScrollbar'].$refs['wrap'].scrollHeight;
                let that = this;

                if (this.$refs['myScrollbar'].wrap.scrollTop === 0 && !this.loading) {
                    this.loading = true;
                    axios.post(this.nextPageUrl, this.friendId).then(res => {
                        let requestInfo = res.data.data;
                        this.nextPageUrl = requestInfo.chat_info.next_page_url;
                        this.loadPage++;
                        requestInfo.chat_info.data.reverse();

                        for (let item in requestInfo.chat_info.data) {
                            requestInfo.chat_info.data[item].isFriendSend = requestInfo.chat_info.data[item].from_user_id === this.friendInfo.user_id;
                        }
                        this.chatInfo.data.unshift.apply(this.chatInfo.data, requestInfo.chat_info.data);

                        setTimeout(() => {
                            let thisScrollHeight = that.$refs['myScrollbar'].$refs['wrap'].scrollHeight;
                            let differenceScrollHeight = thisScrollHeight - previousScrollheight;
                            console.log(differenceScrollHeight)
                            let div = that.$refs['myScrollbar'].$refs['wrap'];
                            that.$nextTick(() => {
                                div.scrollTop = differenceScrollHeight;
                            });
                        }, 2);

                        this.loading = false;

                    }).catch(error => {
                        this.loading = false;
                    })
                }
            },
            getTimeInterval(index) {
                if (index > 0) {
                    let thisCreatedTime = new Date(this.chatInfo.data[index].created_at);
                    let previousCreatedTime = new Date(this.chatInfo.data[index - 1].created_at);
                    let differenceTime = (thisCreatedTime - previousCreatedTime) / 1000;
                    return differenceTime >= 60;
                } else {
                    return true;
                }
            }
        }
    }
</script>

<style scoped src="./chat-detail.css">

</style>
