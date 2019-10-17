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
                                    {{chat.display_time}}
                                </div>
                                <div :class="chat.isFriendSend ? 'item-body-left' : 'item-body-right'"
                                     v-if="chat.content">
                                    <img v-if="chat.isFriendSend" class="friend-avatar" alt=""
                                         :src="chat.isFriendSend ? friendInfo.user_avatar : userInfo.user_avatar"
                                         @click="navigateUser(chat.isFriendSend)">
                                    <div
                                        :class="chat.isFriendSend ? 'bubble-left item-content-left' : 'bubble-right item-content-right'">
                                        {{chat.content}}
                                    </div>
                                    <img v-if="!chat.isFriendSend" alt="" class="friend-avatar"
                                         :src="chat.isFriendSend ? friendInfo.user_avatar : userInfo.user_avatar"
                                         @click="navigateUser(chat.isFriendSend)">
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </el-scrollbar>
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
            axios.post('api/chats/friend', this.friendId).then(res => {
                this.setChatInfo(res.data.data);
                this.computedScrollHeight();
                setTimeout(() => {
                    window.Echo.private('Chat.accept.' + this.userInfo.user_id)
                        .listen('CreateFriendChat', (e) => {
                            this.chatInfo.data.push({
                                'content': e.content,
                                'isFriendSend': true,
                                'created_at': e.created_at
                            });
                            this.unreadNumber++;
                            this.computedScrollHeight();
                        });
                }, 1000);
            })
        },
        mounted() {
            document.addEventListener("scroll", this.watchScroll, true);
        },
        beforeDestroy() {
            document.removeEventListener("scroll", this.watchScroll, true);
        },
        methods: {
            submitInput() {
                if(this.inputContent !== ''){
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
                        });
                        this.inputContent = '';
                    })
                }
            },
            watchScroll() {
                if (this.$refs['myScrollbar'].wrap.scrollTop === 0 && !this.loading) {
                    if (this.loadPage === this.totalPage || this.loading) {
                        return
                    }
                    //计算更细数据前的容器高度
                    let previousScrollHeight = this.$refs['myScrollbar'].$refs['wrap'].scrollHeight;
                    this.loading = true;
                    axios.post(this.nextPageUrl, this.friendId).then(res => {
                        this.updateChatInfo(res.data.data);
                        this.updateScrollHeight(previousScrollHeight);
                    }).catch(error => {
                        this.loading = false;
                    })
                }
            },
            setChatInfo(requestInfo) {
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
            },
            updateChatInfo(requestInfo) {
                setTimeout(() => {
                    this.loading = false;
                }, 200);
                setTimeout(() => {
                    this.nextPageUrl = requestInfo.chat_info.next_page_url;
                    this.loadPage++;
                    requestInfo.chat_info.data.reverse();
                    for (let item in requestInfo.chat_info.data) {
                        requestInfo.chat_info.data[item].isFriendSend = requestInfo.chat_info.data[item].from_user_id === this.friendInfo.user_id;
                    }
                    this.chatInfo.data.unshift.apply(this.chatInfo.data, requestInfo.chat_info.data);
                }, 220);

            },
            updateScrollHeight(previousScrollHeight) {
                setTimeout(() => {
                    let thisScrollHeight = this.$refs['myScrollbar'].$refs['wrap'].scrollHeight;
                    let differenceScrollHeight = thisScrollHeight - previousScrollHeight + 30;
                    let div = this.$refs['myScrollbar'].$refs['wrap'];
                    this.$nextTick(() => {
                        div.scrollTop = differenceScrollHeight;
                    });
                }, 220);
            },
            computedScrollHeight() {
                let div = this.$refs['myScrollbar'].$refs['wrap'];
                this.$nextTick(() => {
                    div.scrollTop = div.scrollHeight;
                })
            },
            navigateUser(isFriend) {
                if (isFriend) {
                    this.$router.push({name: 'friend-info', params: {user: this.friendInfo}});
                } else {
                    console.log('1231231231231231')
                    this.$router.push({name: 'user-info'});
                }
            },
            getTimeInterval(index) {
                if (index > 0) {
                    let thisCreatedTime = new Date(this.chatInfo.data[index].created_at);
                    let previousCreatedTime = new Date(this.chatInfo.data[index - 1].created_at);
                    let differenceTime = (thisCreatedTime - previousCreatedTime) / 1000;
                    this.formatTime(index);
                    return differenceTime >= 60;
                } else {
                    this.formatTime(index);
                    return true;
                }
            },
            formatTime(index) {
                let time = this.chatInfo.data[index].created_at.split(' ');
                let currentTime = new Date().toLocaleDateString();
                let differenceDay = Math.abs(Math.ceil((new Date(currentTime) - new Date(time[0])) / (1000 * 60 * 60 * 24)));
                let differenceWeekDay = 7 - differenceDay;
                if (differenceDay === 0) {
                    this.chatInfo.data[index].display_time = '今天 ' + time[1];
                } else if (differenceDay === 1) {
                    this.chatInfo.data[index].display_time = '昨天' + time[1];
                } else if (differenceDay === 2) {
                    this.chatInfo.data[index].display_time = '前天' + time[1];
                } else {
                    if (differenceWeekDay > 0) {
                        let targetWeekDay = new Date(this.chatInfo.data[index].created_at).getDay();
                        switch (targetWeekDay) {
                            case 0:
                                this.chatInfo.data[index].display_time = '星期天 ' + time[1];
                                break;
                            case 1:
                                this.chatInfo.data[index].display_time = '星期一 ' + time[1];
                                break;
                            case 2:
                                this.chatInfo.data[index].display_time = '星期二 ' + time[1];
                                break;
                            case 3:
                                this.chatInfo.data[index].display_time = '星期三 ' + time[1];
                                break;
                            case 4:
                                this.chatInfo.data[index].display_time = '星期四 ' + time[1];
                                break;
                            case 5:
                                this.chatInfo.data[index].display_time = '星期五 ' + time[1];
                                break;
                            case 6:
                                this.chatInfo.data[index].display_time = '星期六 ' + time[1];
                                break;
                        }
                    } else {
                        this.chatInfo.data[index].display_time = this.chatInfo.data[index].created_at;
                    }
                }
            }
        }
    }
</script>

<style scoped src="./chat-detail.css">

</style>
