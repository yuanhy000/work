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
                    <div class="friend-title">好友</div>
                </div>
            </div-scroll>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "friend-list"
    }
</script>

<style scoped src="./friend-list.css">

</style>
