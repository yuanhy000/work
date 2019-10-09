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
                            <img src="./../../../image/select-down.svg" alt="" class="group-select-img-down"
                                 v-if="friendGroup.display === true">
                            <img src="./../../../image/select-right.svg" alt="" class="group-select-img"
                                 v-else>
                            <!--                            <div class="triangle"></div>-->
                            <div class="group-name">{{friendGroup.friend_group_name}}</div>
                            <transition name="fade">
                                <div class="online-user" v-on:mouseover="mouseOnSelect(index)"
                                     v-if="friendGroup.displayMenu === false">
                                    ({{friendGroup.online_count}}/{{friendGroup.total_count}})
                                </div>
                            </transition>
                            <transition name="fade">
                                <img src="./../../../image/select-menu.svg" alt="" class="select-menu"
                                     v-if="friendGroup.displayMenu === true" :id="'menu'+index"
                                     v-on:mouseout="mouseOutSelect(index)" @click.stop="selectMenu(index)">
                            </transition>
                        </div>
                        <el-collapse-transition>
                            <div class="friends" v-if="friendGroup.display === true">
                                <div class="friend-item-container" v-for="friend in friendGroup.friends"
                                     @click="navigateUser(friend.friend_info)">
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
                    @addGroup="addFriendGroup">
        </selectMenu>
        <AddFriendGroup v-show="displayAddGroup"></AddFriendGroup>
    </div>
</template>

<script>
    import selectMenu from './select-menu';
    import AddFriendGroup from './../add-friendGroup/add-friendGroup';

    export default {
        name: "friend-list",
        components: {
            selectMenu,
            AddFriendGroup
        },
        data() {
            return {
                friendGroups: [],
                displayMenu: false,
                marginTop: 0,
                selectMenuIndex: 0,
                selectDom: {},
                canDelete: true,
                displayAddGroup: false,
            }
        },
        created() {
            axios.post('api/users/friend_group').then(res => {
                this.friendGroups = res.data.data.friendGroups;
                for (let item in this.friendGroups) {
                    this.friendGroups[item].display = false;
                    this.friendGroups[item].displayMenu = false;
                }
            })
        },
        beforeDestroy() {
            document.removeEventListener("click", this.clickEvent2);
        },
        methods: {
            addFriendGroup() {
                this.displayAddGroup = true;
                this.displayMenu = false;
            },
            selectMenu(index) {
                if (index === 0) {
                    this.canDelete = false;
                } else {
                    this.canDelete = true;
                }
                document.addEventListener("click", this.clickEvent2);
                let selectDom = document.getElementById('menu' + index).getBoundingClientRect();
                this.marginTop = selectDom.top - 70;
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
            clickEvent2(e) {
                let selectMenu = document.getElementById('groupMenu');
                if (!selectMenu.contains(e.target) && !this.selectDom.contains(e.target)) {
                    this.displayMenu = false;
                }
            }
        }
    }
</script>

<style scoped src="./friend-list.css">

</style>
