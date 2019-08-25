<template>
    <div class="addition-container">
        <div class="addition-tab">
            <div class="addition-item">
                添加群组
            </div>
            <router-link class="addition-item active" to="/search-user">
                添加好友
            </router-link>
            <div class="addition-item">
                创建群组
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "add-friend"
    }
</script>

<style scoped src="./addition.css">

</style>
