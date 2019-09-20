<template>
    <div class="out-container">
        <div class="friend-list-container">
            <div-scroll color="rgba(0,0,0,0.5)" size="5">
                <div class="friend-add-container">
                    <router-link class="btn friend-add-button" to="/user-info">
                        基本信息
                    </router-link>
                </div>
                <div class="friend-add-container">
                    <router-link class="btn friend-add-button" to="/user-account">
                        账户信息
                    </router-link>
                </div>
<!--                <div class="friend-add-container">-->
<!--                    <router-link class="btn friend-add-button" to="/search-user">-->
<!--                        添加好友-->
<!--                    </router-link>-->
<!--                </div>-->
<!--                <div class="friend-add-container">-->
<!--                    <router-link class="btn friend-add-button" to="/search-user">-->
<!--                        添加好友-->
<!--                    </router-link>-->
<!--                </div>-->
            </div-scroll>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "manage-wrapper"
    }
</script>

<style scoped src="./user-manage.css">

</style>
