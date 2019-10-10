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
                            <div class="group-name-container">
                                <img src="./../../../image/select-down.svg" alt="" class="group-select-img-down"
                                     v-if="friendGroup.display === true">
                                <img src="./../../../image/select-right.svg" alt="" class="group-select-img"
                                     v-else>
                                <div class="group-name">{{friendGroup.friend_group_name}}</div>
                            </div>
                            <!--                            <transition name="fade">-->
                            <div class="online-user" v-on:mouseover="mouseOnSelect(index)"
                                 v-if="friendGroup.displayMenu === false">
                                ({{friendGroup.online_count}}/{{friendGroup.total_count}})
                            </div>
                            <!--                            </transition>-->
                            <!--                            <transition name="fade">-->
                            <img src="./../../../image/select-menu.svg" alt="" class="select-menu"
                                 v-if="friendGroup.displayMenu === true" :id="'menu'+index"
                                 v-on:mouseout="mouseOutSelect(index)" @click.stop="selectMenu(index)">
                            <!--                            </transition>-->
                        </div>
                        <el-collapse-transition>
                            <div class="friends" v-if="friendGroup.display === true">
                                <div class="friend-item-container" v-for="friend in friendGroup.friends"
                                     @click="navigateUser(friend.friend_info)"
                                     v-if="friend.friend_info.user_display">
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
                        </el-collapse-transition>
                    </div>
                </div>
            </div-scroll>
        </div>
        <router-view>
        </router-view>
        <selectMenu v-show="displayMenu" :top="marginTop" id="groupMenu" :canDelete="canDelete"
                    @addGroup="addFriendGroup" @renameGroup="renameFriendGroup"
                    @deleteGroup="deleteFriendGroup" @showOnline="showOnline" :online="onlyOnline">
        </selectMenu>
        <ChangeFriendGroup v-show="displayChangeGroup" @option="changeAction" :title="optionTitle">
        </ChangeFriendGroup>
        <toast v-show="showDeleteToast" style="z-index:999;" @option="deleteAction"
               :title="toastTitle" :content="toastContent"></toast>
    </div>
</template>

<script>
    import selectMenu from './select-menu';
    import ChangeFriendGroup from '../change-friendGroup/change-friendGroup';
    import toast from "../toast/toast";

    export default {
        name: "friend-list",
        components: {
            selectMenu,
            ChangeFriendGroup,
            toast
        },
        data() {
            return {
                screenHeight: 0,
                friendGroups: [],
                displayMenu: false,
                marginTop: 0,
                selectMenuIndex: 0,
                selectDom: {},
                canDelete: true,
                displayChangeGroup: false,
                optionTitle: '',
                toastTitle: '',
                showDeleteToast: false,
                toastContent: '',
                onlyOnline: false
            }
        },
        created() {
            axios.post('api/users/friend_group').then(res => {
                this.setFriendGroupInfo(res);
            });
            this.screenHeight = window.innerHeight ||
                document.documentElement.clientHeight || document.body.clientHeight;
        },
        beforeDestroy() {
            document.removeEventListener("click", this.clickEvent);
        },
        methods: {
            deleteFriendGroup() {
                this.showDeleteToast = true;
                this.displayMenu = false;
                this.toastTitle = '确认删除';
                this.toastContent = '确认删除该好友分组吗?';
            },
            deleteAction(option) {
                this.showDeleteToast = false;
                if (option) {
                    let group_id = this.friendGroups[this.selectMenuIndex].friend_group_id;
                    axios.post('api/friend_groups/delete', group_id).then(res => {
                        this.friendGroups.splice(this.selectMenuIndex, 1);
                    }).catch(error => {
                        this.$message({
                            message: error.response.data.msg,
                            type: 'error'
                        });
                    })
                }
            },
            renameFriendGroup() {
                this.displayChangeGroup = true;
                this.displayMenu = false;
                this.optionTitle = '重命名该组';
            },
            addFriendGroup() {
                this.displayChangeGroup = true;
                this.displayMenu = false;
                this.optionTitle = '添加分组';
            },
            changeAction(res) {
                this.displayChangeGroup = false;
                if (this.optionTitle === '添加分组') {
                    if (res.option) {
                        axios.post('api/friend_groups/add', res.input).then(res => {
                            this.setFriendGroupInfo(res);
                        }).catch(error => {
                            this.$message({
                                message: error.response.data.msg,
                                type: 'error'
                            });
                        })
                    }
                } else if (this.optionTitle === '重命名该组') {
                    if (res.option) {
                        let data = {
                            group_id: this.friendGroups[this.selectMenuIndex].friend_group_id,
                            input: res.input
                        };
                        axios.post('api/friend_groups/rename', data).then(res => {
                            this.friendGroups[this.selectMenuIndex].friend_group_name = data.input;
                        }).catch(error => {
                            this.$message({
                                message: error.response.data.msg,
                                type: 'error'
                            });
                        })
                    }
                }
            },
            selectMenu(index) {
                document.addEventListener("click", this.clickEvent);
                this.canDelete = this.index !== 0 && this.friendGroups[index].total_count === 0;
                let selectDom = document.getElementById('menu' + index).getBoundingClientRect();
                this.marginTop = selectDom.top - 70;
                if (this.marginTop > this.screenHeight - 220) {
                    this.marginTop = this.screenHeight - 220 - 70;
                }
                this.displayMenu = !this.displayMenu;
                if (index !== this.selectMenuIndex) {
                    this.displayMenu = true;
                }
                this.selectMenuIndex = index;
                this.selectDom = document.getElementById('menu' + index);
            },
            selectFriendGroup(index) {
                let obj = this.friendGroups[index];
                obj.display = !obj.display;
                this.$set(this.friendGroups, index, obj);
            },
            navigateUser(user) {
                this.$router.push({name: 'friend-info', params: {user: user}});
            },
            mouseOnSelect(index) {
                let obj = this.friendGroups[index];
                obj.displayMenu = true;
                this.$set(this.friendGroups, index, obj);
            },
            mouseOutSelect(index) {
                let obj = this.friendGroups[index];
                obj.displayMenu = false;
                this.$set(this.friendGroups, index, obj);
            },
            clickEvent(e) {
                let selectMenu = document.getElementById('groupMenu');
                if (!selectMenu.contains(e.target) && !this.selectDom.contains(e.target)) {
                    this.displayMenu = false;
                }
            },
            setFriendGroupInfo(res) {
                this.friendGroups = res.data.data.friendGroups;
                for (let item in this.friendGroups) {
                    this.friendGroups[item].display = false;
                    this.friendGroups[item].displayMenu = false;
                }
            },
            showOnline() {
                this.displayMenu = false;
                this.onlyOnline = !this.onlyOnline;
                for (let index in this.friendGroups) {
                    for (let item in this.friendGroups[index].friends) {
                        let obj = this.friendGroups[index].friends[item];
                        if (this.onlyOnline === true) {
                            obj.friend_info.user_display = obj.friend_info.user_status === 1;
                        } else {
                            obj.friend_info.user_display = true;
                        }
                        this.$set(this.friendGroups[index].friends, item, obj);
                    }
                }
            },
        }
    }
</script>

<style scoped src="./friend-list.css">

</style>
