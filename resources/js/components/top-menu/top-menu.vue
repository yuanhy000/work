<template>
    <nav class="menu-container">
        <router-link class="menu-title" to="/">Project</router-link>
        <div class="menu-operation">
            <router-link class="menu-login" to="/login">
                <button class="btn menu-button">登陆</button>
            </router-link>
            <router-link class="menu-login" to="/register">
                <button class="btn menu-button">注册</button>
            </router-link>
        </div>
    </nav>

</template>

<script>
    export default {
        name: "top-menu"
    }
</script>

<style scoped src="./top-menu.css">

</style>
