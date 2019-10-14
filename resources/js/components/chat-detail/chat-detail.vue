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
            <div-scroll color="rgba(0,0,0,0.5)" size="5">
                <div>
                    <div class="item-content-container" v-if="chatInfo" v-for="(chat,index) in chatInfo.data">
                        <div class="item-time" v-if="getTimeInterval(index)">
                            {{chat.created_at}}
                        </div>
                        <div :class="chat.isFriendSend ? 'item-body-left' : 'item-body-right'" v-if="chat.content">
                            <img v-if="chat.isFriendSend" class="friend-avatar"
                                 :src="chat.isFriendSend ? friendInfo.user_avatar : userInfo.user_avatar" alt="">
                            <div
                                :class="chat.isFriendSend ? 'bubble-left item-content-left' : 'bubble-right item-content-right'">
                                {{chat.content}}
                            </div>
                            <img v-if="!chat.isFriendSend" alt="" class="friend-avatar"
                                 :src="chat.isFriendSend ? friendInfo.user_avatar : userInfo.user_avatar">
                        </div>
                    </div>
                </div>
            </div-scroll>
        </div>
        <div class="chat-input-container">

        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "chat-detail",
        data() {
            return {
                friendName: '',
                friendInfo: [],
                chatInfo: [],
            }
        },
        computed: {
            ...mapState({
                userInfo: state => state.AuthUser
            }),
        },
        created() {
            let friend_id = this.$route.params.friend_id;
            axios.post('api/chats/friend', friend_id).then(res => {
                let requestInfo = res.data.data;
                this.friendInfo = requestInfo.friend_info;
                this.chatInfo = requestInfo.chat_info;
                this.friendName = requestInfo.friend_name;
                this.chatInfo.data.reverse();
                for (let item in this.chatInfo.data) {
                    this.chatInfo.data[item].isFriendSend = this.chatInfo.data[item].from_user_id === this.friendInfo.user_id;
                }
            })
        },
        methods: {
            getTimeInterval(index) {
                if (index > 0) {
                    let thisCreatedTime = new Date(this.chatInfo.data[index].created_at);
                    let previousCreatedTime = new Date(this.chatInfo.data[index - 1].created_at);
                    let differenceTime = (thisCreatedTime - previousCreatedTime) / 1000;
                    if (differenceTime >= 60) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            }
        }
    }
</script>

<style scoped src="./chat-detail.css">

</style>
