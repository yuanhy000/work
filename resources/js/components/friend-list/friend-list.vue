<template>
    <div>
        <div class="friend-list-container">
            <div-scroll color="rgba(0,0,0,0.5)" size="5">
                <div class="friend-add-container">
                    <router-link class="btn friend-add-button" to="/search-user">
                        添加好友
                    </router-link>
                </div>
                <div class="group-container">
                    <div class="group-title">群聊</div>
                </div>
                <div class="group-container">
                    <div class="group-title">好友</div>
                    <div class="friend-group-container" v-for="friendGroup in friendGroups">
                        <div class="group-title">
                            <img src="./../../../image/select-right.svg" alt="" class="group-select-img">
                            <div class="group-name">{{friendGroup.friend_group_name}}</div>
                        </div>
                        <div class="friend-item-container" v-for="friend in friendGroup.friends">
                            <img :src="friend.friend_info.user_avatar" alt="" class="friend-avatar">
                            <div class="friend-info">
                                <div class="friend-name">{{friend.friend_info.user_name}}</div>
                                <div class="friend-signature">{{friend.friend_info.user_signature}}</div>
                            </div>
                            <div class="online2">[ 在线 ]</div>
                            <div class="online"></div>
                            <div class="offline"></div>
                        </div>
                    </div>
                </div>
            </div-scroll>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "friend-list",
        data() {
            return {
                friendGroups: [],
            }
        },
        created() {
            axios.post('api/users/friend_group').then(res => {
                this.friendGroups = res.data.data.friendGroups;
            })
        }
    }
</script>

<style scoped src="./friend-list.css">

</style>
