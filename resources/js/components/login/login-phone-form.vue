<template>
    <form class="login-form" @submit.prevent="login">
        <div class="login-emil">
            <label for="email" class="login-emil-name">手机号</label>
            <input id="email" type="email" class="form-control login-email-input" name="email" required>
        </div>
        <div class="login-emil position-refresh">
            <label for="phone" class="login-emil-name"></label>
            <button class="btn send-button">发送验证码</button>
        </div>
        <div class="login-emil">
            <label for="password" class="login-emil-name">验证码</label>
            <input id="password" type="password" class="form-control login-email-input" name="password" required>
        </div>
        <div class="login-emil">
            <button type="submit" class="btn login-button">
                登录
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        name: "login-form"
    }
</script>

<style scoped src="./login.css">

</style>
