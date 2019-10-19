<template>
    <div>
        <div class="chat-container">
            <el-scrollbar style="height:100%;">
                <div class="chat-item-container" v-for="(chatList,index) in chatLists"
                     @click="navigateChatDetail(index)" :class="selectIndex === index ? 'selected' : ''">
                    <img :src="chatList.friend_info.user_avatar" alt="" class="friend-avatar">
                    <div class="friend-info">
                        <div class="info-content">
                            <div class="friend-name">{{chatList.friend_name}}</div>
                            <!--                            <div class="friend-status">-->
                            <div class="friend-signature">{{chatList.chat_info.data[0].content}}</div>
                            <!--                            </div>-->
                        </div>
                        <div class="info-other">
                            <div class="last-time">{{chatList.chat_info.data[0].display_time}}</div>
                            <div v-if="chatList.unread_number<=99 && chatList.unread_number>0"
                                 class="unread-number">{{chatList.unread_number}}
                            </div>
                            <div class="unread-number" v-if="chatList.unread_number>99">99+</div>
                            <div class="position" v-else></div>
                        </div>
                    </div>
                </div>
            </el-scrollbar>
        </div>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "home",
        data() {
            return {
                chatLists: [],
                selectIndex: -1,
            }
        },
        created() {
            axios.post('api/chats/list').then(res => {
                console.log(res.data.data)
                this.chatLists = res.data.data.chats;
                this.formatTime();
            })
        },
        methods: {
            navigateChatDetail(index) {
                this.selectIndex = index;
                this.chatLists[index].unread_number = 0;
                this.$router.push({
                    name: 'chat-detail', params: {
                        friend_id: this.chatLists[index].friend_info.user_id
                    }
                });
            },
            formatTime() {
                for (let index in this.chatLists) {
                    let time = this.chatLists[index].chat_info.data[0].created_at.split(' ');
                    let detailTime = time[1].split(':');
                    let currentTime = new Date().toLocaleDateString();
                    let differenceDay = Math.abs(Math.ceil((new Date(currentTime) - new Date(time[0])) / (1000 * 60 * 60 * 24)));
                    let differenceWeekDay = 7 - differenceDay;
                    if (differenceDay === 0) {
                        this.chatLists[index].chat_info.data[0].display_time = '今天 ' + detailTime[0] + ':' + detailTime[1];
                    } else if (differenceDay === 1) {
                        this.chatLists[index].chat_info.data[0].display_time = '昨天 ' + detailTime[0] + ':' + detailTime[1];
                    } else if (differenceDay === 2) {
                        this.chatLists[index].chat_info.data[0].display_time = '前天 ' + detailTime[0] + ':' + detailTime[1];
                    } else {
                        if (differenceWeekDay > 0) {
                            let targetWeekDay = new Date(this.chatLists[index].chat_info.data[0].created_at).getDay();
                            switch (targetWeekDay) {
                                case 0:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期天 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                                case 1:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期一 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                                case 2:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期二 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                                case 3:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期三 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                                case 4:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期四 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                                case 5:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期五 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                                case 6:
                                    this.chatLists[index].chat_info.data[0].display_time = '星期六 ' + detailTime[0] + ':' + detailTime[1];
                                    break;
                            }
                        } else {
                            this.chatLists[index].chat_info.data[0].display_time = this.chatLists[index].chat_info.data[0].created_at;
                        }
                    }
                }
            }

        }
    }
</script>

<style scoped src="./chat.css">

</style>
