<template>
    <form class="login-form" @submit.prevent="login">
        <div class="register-item">
            <label for="email" class="login-emil-name">用户名</label>
            <input id="username" type="email" class="form-control login-email-input" name="email" required>
        </div>
        <div class="register-item">
            <label for="email" class="login-emil-name">邮箱</label>
            <input id="email" type="email" class="form-control login-email-input" name="email" required>
        </div>
        <div class="register-item">
            <label for="email" class="login-emil-name">密码</label>
            <input id="password" type="email" class="form-control login-email-input" name="email" required>
        </div>
        <div class="register-item">
            <label for="email" class="login-emil-name">确认密码</label>
            <input id="confirm-password" type="email" class="form-control login-email-input" name="email" required>
        </div>
        <div class="register-item">
            <label for="phone" class="login-emil-name">手机号</label>
            <input id="phone" type="email" class="form-control login-email-input" name="email" required>
        </div>
        <div class="register-item">
            <label for="phone" class="login-emil-name"></label>
            <button class="btn register-send-button">发送验证码</button>
        </div>
        <div class="register-item">
            <label for="password" class="login-emil-name">验证码</label>
            <input id="code" type="password" class="form-control login-email-input" name="password" required>
        </div>
        <div class="register-item">
            <button type="submit" class="btn login-button">
                注册
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
