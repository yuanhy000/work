<template>
    <div>
        <div class="chat-container">
            <el-scrollbar style="height:100%;">
                <div class="chat-item-container" v-for="(chatList,index) in chatLists"
                     @click="navigateChatDetail(index)">
                    <img :src="chatList.friend_info.user_avatar" alt="" class="friend-avatar">
                    <div class="friend-info">
                        <div class="friend-name">{{chatList.friend_name}}</div>
                        <div class="friend-status">
                            <div class="friend-signature">{{chatList.chat_info.data[0].content}}</div>
                        </div>
                    </div>
                </div>
            </el-scrollbar>
        </div>
        <router-view>
        </router-view>
    </div>
</template>

<script>
    export default {
        name: "home",
        data() {
            return {
                chatLists: []
            }
        },
        created() {
            axios.post('api/chats/list').then(res => {
                console.log(res.data.data)
                this.chatLists = res.data.data.chats;
            })
        },
        methods: {
            navigateChatDetail(index) {
                this.$router.push({
                    name: 'chat-detail', params: {
                        friend_id: this.chatLists[index].friend_info.user_id
                    }
                });
            },
        }
    }
</script>

<style scoped src="./chat.css">

</style>
