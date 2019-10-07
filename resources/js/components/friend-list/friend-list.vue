<template>
    <div>
        <div class="friend-list-container">
            <div class="friend-add-container">
                <router-link class="btn friend-add-button" to="/search-user">
                    添加好友
                </router-link>
            </div>
            <div-scroll color="rgba(0,0,0,0.5)" size="5">
                <div class="group-container">
                    <div class="group-title">群聊</div>
                </div>
                <div class="group-container">
                    <div class="group-title">好友</div>
                    <div class="friend-group-container" v-for="(friendGroup,index) in friendGroups">
                        <div class="group-title" @click="selectFriendGroup(index)">
                            <img src="./../../../image/select-right.svg" alt="" class="group-select-img">
                            <div class="group-name">{{friendGroup.friend_group_name}}</div>
                        </div>
                        <div class="friends" v-if="friendGroup.display === true">
                            <div class="friend-item-container" v-for="friend in friendGroup.friends">
                                <img :src="friend.friend_info.user_avatar" alt="" class="friend-avatar">
                                <div class="friend-info">
                                    <div class="friend-name">{{friend.friend_info.user_name}}</div>
                                    <div class="friend-status">
                                        <div class="online" v-if="friend.friend_info.user_status ===1">[在线]</div>
                                        <div class="online" v-else>[离线]</div>
                                        <div class="friend-signature">{{friend.friend_info.user_signature}}</div>
                                    </div>
                                </div>
                                <div class="online"></div>
                                <div class="offline"></div>
                            </div>
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
                display: false
            }
        },
        created() {
            axios.post('api/users/friend_group').then(res => {
                this.friendGroups = res.data.data.friendGroups;
                for (let item in this.friendGroups) {
                    this.friendGroups[item].display = false;
                }
            })
        },
        methods: {
            selectFriendGroup(index) {
                console.log(this.friendGroups[index].display);
                let obj = this.friendGroups[index];
                obj.display = !obj.display;
                this.$set(this.friendGroups, index, obj);
                // this.friendGroups[index].display = !this.friendGroups[index].display;
                // this.$set(this.friendGroups[index].display, index, this.friendGroups);
                // this.display = !this.display;
            }
        }
    }
</script>

<style scoped src="./friend-list.css">

</style>
