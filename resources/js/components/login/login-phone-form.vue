<template>
    <form class="login-form" @submit.prevent="login">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="login-emil">
            <label for="phone" class="login-emil-name">手机号</label>
            <input v-model="phone" v-validate="'required|phone'" data-vv-as="手机号码"
                   id="phone" :class="{'error-input' : errors.has('phone') ||errorBag.has('phone') }"
                   class="form-control login-email-input" name="phone" required>
        </div>
        <div class="error-info-container" v-show="errors.has('phone') ||errorBag.has('phone')">
            <label class="login-emil-name"></label>
            <span class="form-text" v-show="errors.has('phone')">{{errors.first('phone')}}</span>
            <span class="form-text" v-if="mismatchError('phone')">{{errorBag.first('phone')}}</span>
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
                   id="code" :class="{'error-input' : errors.has('code') ||errorBag.has('code') }"
                   class="form-control login-email-input" name="code" required>
        </div>
        <div class="error-info-container" v-show="errors.has('code') ||errorBag.has('code')">
            <label class="login-emil-name"></label>
            <span class="form-text" v-show="errors.has('code')">{{errors.first('code')}}</span>
            <span class="form-text" v-if="mismatchError('code')">{{errorBag.first('code')}}</span>
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

    import loading from './../loading/loading';
    import {ErrorBag} from 'vee-validate';

    export default {
        name: "login-form",
        components: {
            loading: loading
        },
        data() {
            return {
                phone: '',
                code: '',
                countDownTime: 10,
                canClick: true,
                buttonContent: '发送验证码',
                errorBag: new ErrorBag(),
                loading: false
            }
        },
        watch: {
            'phone': function () {
                this.errorBag.remove('phone');
            },
            'code': function () {
                this.errorBag.remove('code');
            },
        },
        methods: {
            mismatchError(filed) {
                return this.errorBag.has(filed) && !this.errors.has(filed);
            },
            login() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.loading = true;
                        let loginInfo = {
                            phone: this.phone,
                            code: this.code,
                            type: 'phone'
                        };
                        this.$store.dispatch('loginRequest', loginInfo).then(res => {
                            this.loading = false;
                            this.$router.push({name: 'chat'});
                        }).catch(error => {
                            this.loading = false;
                            if (error.response.status === 404) {
                                this.errorBag.add({
                                    field: 'code',
                                    msg: error.response.data.message,
                                });
                            } else if (error.response.status === 409) {
                                this.errorBag.add({
                                    field: 'phone',
                                    msg: error.response.data.message,
                                });
                            }
                        });
                    }
                });
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
