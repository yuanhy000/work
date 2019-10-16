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
            <el-scrollbar style="height:100%;" ref="myScrollbar">
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
            <div class="operation-container">
            </div>
            <textarea name="" id="" class="input-content" v-model="inputContent"></textarea>
            <button class="submit-button btn" @click="submitInput">发送</button>
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
                chatId: 0,
                friendId: 0,
                friendName: '',
                friendInfo: [],
                chatInfo: [],
                loading: false,
                nextPageUrl: '',
                loadPage: 0,
                totalPage: 0,
                locked: false,
                inputContent: '',
                unreadNumber: 0,
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
                this.chatId = requestInfo.chat_id;
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
            console.log('Chat.accept.' + this.userInfo.user_id);
            window.Echo.private('Chat.accept.' + this.userInfo.user_id)
                .listen('CreateFriendChat', (e) => {
                    this.chatInfo.data.push({
                        'content': e.content,
                        'isFriendSend': true,
                        'created_at': e.created_at
                    });
                    this.unreadNumber++;
                    let div = this.$refs['myScrollbar'].$refs['wrap'];
                    this.$nextTick(() => {
                        div.scrollTop = div.scrollHeight;
                    })
                });
        },
        beforeDestroy() {
            document.removeEventListener("scroll", this.checkScroll, true);
        },
        methods: {
            submitInput() {
                axios.post('api/chats/create', {
                    inputContent: this.inputContent,
                    accept_id: this.friendId,
                    chat_id: this.chatId
                }).then(res => {
                    this.chatInfo.data.push({
                        'content': this.inputContent,
                        'isFriendSend': false,
                        'created_at': res.data.created_at
                    });
                    let div = this.$refs['myScrollbar'].$refs['wrap'];
                    this.$nextTick(() => {
                        div.scrollTop = div.scrollHeight;
                    })
                    this.inputContent = '';
                })
            },
            checkScroll() {
                // console.log(this.$refs['myScrollbar'].wrap.scrollTop);
                let previousScrollheight = this.$refs['myScrollbar'].$refs['wrap'].scrollHeight;
                let that = this;

                if (this.$refs['myScrollbar'].wrap.scrollTop === 0 && !this.loading) {
                    if (this.loadPage === this.totalPage || this.loading) {
                        return
                    }
                    // if (this.locked) {
                    //     return;
                    // }
                    this.loading = true;
                    // this.locked = true;
                    axios.post(this.nextPageUrl, this.friendId).then(res => {
                        setTimeout(() => {
                            this.loading = false;
                        }, 200);
                        setTimeout(() => {
                            let requestInfo = res.data.data;
                            this.nextPageUrl = requestInfo.chat_info.next_page_url;
                            this.loadPage++;
                            requestInfo.chat_info.data.reverse();

                            for (let item in requestInfo.chat_info.data) {
                                requestInfo.chat_info.data[item].isFriendSend = requestInfo.chat_info.data[item].from_user_id === this.friendInfo.user_id;
                            }
                            this.chatInfo.data.unshift.apply(this.chatInfo.data, requestInfo.chat_info.data);
                        }, 220);

                        setTimeout(() => {
                            let thisScrollHeight = that.$refs['myScrollbar'].$refs['wrap'].scrollHeight;
                            let differenceScrollHeight = thisScrollHeight - previousScrollheight + 30;
                            console.log(differenceScrollHeight)
                            let div = that.$refs['myScrollbar'].$refs['wrap'];
                            that.$nextTick(() => {
                                div.scrollTop = differenceScrollHeight;
                            });
                        }, 220);

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
