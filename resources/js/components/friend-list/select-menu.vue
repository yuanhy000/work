<template>
    <div class="manage-list" :style="{marginTop:top+'px'}">
        <div class="item-top" @click="addFriendGroup">添加组</div>
        <div class="item-center" @click="renameFriendGroup">重命名该组</div>
        <div class="item-center" :class="canDelete?'':'disableDelete'" @click="deleteFriendGroup">删除该组</div>
        <div class="item-bottom" @click="showOnline">
            <img src="./../../../image/select.svg" alt="" class="item-online-img" v-show="online">
            <div>仅显示在线联系人</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "select-menu",
        props: {
            top: 0,
            canDelete: true,
            online: false,
        },
        methods: {
            addFriendGroup() {
                this.$emit('addGroup');
            },
            renameFriendGroup() {
                this.$emit('renameGroup');
            },
            deleteFriendGroup() {
                this.$emit('deleteGroup');
            },
            showOnline() {
                this.$emit('showOnline');
            },
        }
    }
</script>

<style scoped src="./friend-list.css">

</style>
