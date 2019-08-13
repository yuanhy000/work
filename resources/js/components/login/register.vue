<template>
    <div class="login-container">
        <div class="login-content">
            <div class="login-top">
                <div class="login ">
                    <router-link class="login-title " to="/login">登陆</router-link>
                </div>
                <div class="login title-decoration">
                    <router-link class="login-title" to="/register">注册</router-link>
                </div>
            </div>

            <!--            <router-view class="form"></router-view>-->
            <register-form class="register-form-container"></register-form>

        </div>
    </div>
</template>

<script>
    export default {
        name: "login"
    }
</script>

<style scoped src="./login.css">

</style>
