<template>
    <form class="login-form" @submit.prevent="login">
        <div class="login-emil">
            <label for="phone" class="login-emil-name">手机号</label>
            <input v-model="phone" v-validate="'required|phone'" data-vv-as="手机号码"
                   id="email" :class="{'error-input' : errors.has('phone') }"
                   class="form-control login-email-input" name="phone" required>
        </div>
        <div class="error-info-container" v-show="errors.has('phone')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('phone')}}</span>
        </div>
        <div class="login-emil position-refresh">
            <label class="login-emil-name"></label>
            <button class="btn send-button" :class="{'disabled-button' :!canClick}"
                    @click="getCode" type="button">{{buttonContent}}
            </button>
        </div>
        <div class="login-emil">
            <label for="code" class="login-emil-name">验证码</label>
            <input v-model="code" v-validate="'required|length:6'" data-vv-as="验证码"
                   id="code" :class="{'error-input' : errors.has('code') }"
                   class="form-control login-email-input" name="code" required>
        </div>
        <div class="error-info-container" v-show="errors.has('code')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('code')}}</span>
        </div>
        <div class="login-emil">
            <button type="submit" class="btn login-button"
                    :class="{'disabled-button' : errors.any()}">
                登录
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        name: "login-form",
        data() {
            return {
                phone: '',
                code: '',
                countDownTime: 10,
                canClick: true,
                buttonContent: '发送验证码',
            }
        },
        methods: {
            login() {
                let loginInfo = {
                    email: this.email,
                    password: this.password,
                    type: 'email'
                };
                console.log(loginInfo);
                return axios.post('/api/login', loginInfo).then(res => {
                    // this.$router.push({name: 'confirm'})
                    console.log(res)
                })
            },
            getCode() {
                if (!this.canClick) {
                    return;
                }
                this.canClick = false;
                this.buttonContent = this.countDownTime + 's 后重新发送';
                let clock = window.setInterval(() => {
                    this.countDownTime--;
                    this.buttonContent = this.countDownTime + 's 后重新发送';
                    if (this.countDownTime <= 0) {
                        window.clearInterval(clock);
                        this.buttonContent = '发送验证码';
                        this.countDownTime = 10;
                        this.canClick = true;
                    }
                }, 1000);

                let phone = {
                    phone: this.phone
                };

                return axios.post('/api/login/phone/code', phone).then(res => {
                    console.log(res);
                })
            }
        }
    }
</script>

<style scoped src="./login.css">

</style>
