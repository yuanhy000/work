<template>
    <div class="manage-list" :style="{marginTop:top+'px'}">
        <div class="item-top" @click="addFriendGroup">添加组</div>
        <div class="item-center">重命名改组</div>
        <div class="item-center" :class="canDelete?'':'disableDelete'">删除改组</div>
        <div class="item-bottom">仅显示在线联系人</div>
    </div>
</template>

<script>
    export default {
        name: "select-menu",
        props: {
            top: 0,
            canDelete: true
        },
        methods: {
            addFriendGroup() {
                    this.$emit('addGroup');
            }
        }
    }
</script>

<style scoped src="./friend-list.css">

</style>
