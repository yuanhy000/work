<template>
    <div class="login-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="login-content">
            <div class="login-top">
                <div class="login title-decoration">
                    <router-link class="login-title " to="/login">登陆</router-link>
                </div>
                <div class="login">
                    <router-link class="login-title" to="/register">注册</router-link>
                </div>
            </div>

            <router-view class="login-form-container"></router-view>
            <!--            <login-form class="form"></login-form>-->
            <div class="login-type">
                <div class="login-type-title">登陆方式</div>
                <div class="other-login">
                    <div class="item-login-container">
                        <router-link :to="{name: 'email-login'}" class="item-login" activeClass="active" exact>
                            <button class="btn login-type-button">
                                <img class="login-image" src="./../../../image/email.svg" alt="">邮箱登陆
                            </button>
                        </router-link>
                    </div>
                    <div class="item-login-container">
                        <a class="item-login" href="/github/login" @click="setLoading">
                            <button class="btn login-type-button">
                                <img class="login-image" src="./../../../image/github1.svg" alt="">GitHub
                            </button>
                        </a>
                    </div>
                    <div class="item-login-container">
                        <router-link :to="{name: 'phone-login'}" class="item-login" activeClass="active" exact>
                            <button class="btn login-type-button">
                                <img class="login-image" src="./../../../image/phone.svg" alt="">手机验证码
                            </button>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import loading from './../loading/loading';

    export default {
        name: "login",
        components: {
            loading: loading
        },
        data() {
            return {
                loading: false
            }
        },
        methods: {
            setLoading() {
                this.loading = true;
            }
        }
    }

</script>

<style scoped src="./login.css">

</style>
