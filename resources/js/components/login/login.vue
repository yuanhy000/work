<template>
    <div class="login-container">
        <div class="login-content">
            <div class="login-top">
                <div class="login title-decoration">
                    <router-link class="login-title " to="/login">登陆</router-link>
                </div>
                <div class="login">
                    <router-link class="login-title" to="/register">注册</router-link>
                </div>
            </div>
            <login-form class="form"></login-form>
            <div class="login-type">
                <div class="login-type-title">登陆方式</div>
                <div class="other-login">
                    <div class="item-login-container">
                        <a class="item-login">
                            <button class="btn login-type-button">
                                <img class="login-image" src="./../../../image/email.svg" alt="">邮箱密码
                            </button>
                        </a>
                    </div>
                    <div class="item-login-container">
                        <a class="item-login">
                            <button class="btn login-type-button">
                                <img class="login-image" src="./../../../image/github1.svg" alt="">GitHub
                            </button>
                        </a>
                    </div>
                    <div class="item-login-container">
                        <a class="item-login">
                            <button class="btn login-type-button">
                                <img class="login-image" src="./../../../image/phone.svg" alt="">手机验证码
                            </button>
                        </a>
                    </div>
                </div>
            </div>
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
