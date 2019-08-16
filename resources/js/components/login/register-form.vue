<template>
    <form class="login-form" @submit.prevent="register">
        <div class="register-item">
            <label for="userName" class="login-emil-name">用户名</label>
            <input v-model="userName" v-validate="'required|min:6'" data-vv-as="用户名"
                   id="userName" :class="{'error-input' : errors.has('userName') }"
                   class="form-control login-email-input" name="userName" required>
        </div>
        <div class="error-info-container" v-show="errors.has('userName')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('userName')}}</span>
        </div>
        <div class="register-item">
            <label for="email" class="login-emil-name">邮箱</label>
            <input v-model="email" v-validate="'required|email'" data-vv-as="邮箱"
                   id="email" :class="{'error-input' : errors.has('email') }"
                   class="form-control login-email-input" name="email" required>
        </div>
        <div class="error-info-container" v-show="errors.has('email')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('email')}}</span>
        </div>
        <div class="register-item">
            <label for="password" class="login-emil-name">密码</label>
            <input v-model="password" v-validate="'required|min:9'" data-vv-as="密码"
                   id="password" :class="{'error-input' : errors.has('password') }" ref="password"
                   type="password" class="form-control login-email-input" name="password" required>
        </div>
        <div class="error-info-container" v-show="errors.has('password')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('password')}}</span>
        </div>
        <div class="register-item">
            <label for="confirmPassword" class="login-emil-name">确认密码</label>
            <input v-model="confirmPassword" v-validate="'required|confirmed:password'" data-vv-as="密码"
                   id="confirmPassword" :class="{'error-input' : errors.has('confirmPassword') }"
                   type="password" class="form-control login-email-input" name="confirmPassword" required>
        </div>
        <div class="error-info-container" v-show="errors.has('confirmPassword')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('confirmPassword')}}</span>
        </div>
        <div class="register-item">
            <label for="phone" class="login-emil-name">手机号</label>
            <input v-model="phone" v-validate="'required|phone'" data-vv-as="手机号码"
                   id="phone" :class="{'error-input' : errors.has('phone') }"
                   class="form-control login-email-input" name="phone" required>
        </div>
        <div class="error-info-container" v-show="errors.has('phone')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('phone')}}</span>
        </div>
        <div class="register-item">
            <label class="login-emil-name"></label>
            <button class="btn register-send-button" :class="{'disabled-button' :!canClick}"
                    @click="getCode" type="button">{{buttonContent}}
            </button>
        </div>
        <div class="register-item">
            <label for="code" class="login-emil-name">验证码</label>
            <input v-model="code" v-validate="'required|length:6'" data-vv-as="验证码"
                   id="code" :class="{'error-input' : errors.has('code') }"
                   class="form-control login-email-input" name="code" required>
        </div>
        <div class="error-info-container" v-show="errors.has('code')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('code')}}</span>
        </div>
        <div class="register-item">
            <button type="submit" class="btn login-button"
                    :class="{'disabled-button' : errors.any()}">
                注册
            </button>
        </div>
    </form>
</template>

<script>
    // import {ErrorBag} from 'vee-validate';
    import {Validator} from 'vee-validate';

    Validator.extend('phone', {
        getMessage: field => field + '格式错误',
        validate: value => {
            return value.length == 11 && /^((13|14|15|17|18|19)[0-9]{1}\d{8})$/.test(value)
        }
    });

    export default {
        name: "login-form",
        data() {
            return {
                userName: '',
                email: '',
                password: '',
                confirmPassword: '',
                phone: '',
                code: '',
                countDownTime: 10,
                canClick: true,
                buttonContent: '发送验证码'
            }
        },
        methods: {
            register() {
                let registerInfo = {
                    userName: this.userName,
                    email: this.email,
                    password: this.password,
                    phone: this.phone,
                    code: this.code
                };
                console.log(registerInfo);
                return axios.post('/api/register', registerInfo).then(res => {
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

                return axios.post('/api/register/phone/code', phone).then(res => {
                    console.log(res);
                })

            }
        }
    }
</script>

<style scoped src="./login.css">

</style>
