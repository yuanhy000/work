<template>
    <div class="user-info-container">
        <loading v-show="loading"></loading>
        <div class="account-container">
            <div class="account-title">
                <img src="./../../../../public/icons/info.svg" alt="" class="title-image">
                <div class="title">账户信息</div>
            </div>
            <div class="account-info">
                <div class="account-item">
                    <div class="item-title">ID</div>
                    <input class="item-input" ref="inputName" readonly :value="userInfo.user_number">
                </div>
                <div class="account-item">
                    <div class="item-title">邮箱</div>
                    <input class="item-input" ref="inputSchool" readonly :value="userInfo.user_email">
                </div>
                <div class="account-item">
                    <div class="item-title">手机</div>
                    <input class="item-input" ref="inputSignature" readonly :value="userInfo.user_phone">
                </div>
                <div class="account-item">
                    <div class="item-title">密码</div>
                    <input class="item-input" ref="inputSignature" type="password" readonly
                           value="default-password">
                </div>
            </div>
        </div>

        <div class="other-container">
            <div class="item-container">
                <div class="account-title">
                    <img src="./../../../image/phone-other.svg" alt="" class="title-image">
                    <div class="title">绑定手机</div>
                </div>
                <div class="title-container">
                    输入绑定的手机号码 :
                </div>
                <input v-model="phone" v-validate="'required|phone'" data-vv-as="手机号码" id="phone"
                       :class="{'error-input' : errors.has('phone') ||errorBag.has('phone') ||errorBag.has('hasPhone')}"
                       class="other-input" name="phone">

                <div class="error-info-container"
                     v-show="errors.has('phone')||errorBag.has('phone')||errorBag.has('hasPhone')">
                    <span class="form-text" v-show="errors.has('phone')">{{errors.first('phone')}}</span>
                    <span class="form-text" v-if="mismatchError('phone')">{{errorBag.first('phone')}}</span>
                    <span class="form-text" v-if="mismatchError('hasPhone')">{{errorBag.first('hasPhone')}}</span>
                </div>

                <verify @success="verifySuccess('phone')" @error="verifyFailed('phone')"
                        :type="3" :showButton="false" class="verify" :barSize="{width:'60%',height:'40px'}">
                </verify>
                <div class="error-info-container" v-show="errorBag.has('verifyPhone')">
                    <span class="form-text-left">{{errorBag.first('verifyPhone')}}</span>
                </div>
                <button class="btn send-button" :class="{'disabled-button' :!canClick}"
                        @click="getCode" type="button">{{buttonContent}}
                </button>
                <div class="title-container">
                    输入验证码 :
                </div>
                <input v-model="code" v-validate="'required|length:6'" data-vv-as="验证码"
                       id="code" :class="{'error-input' : errors.has('code')||errorBag.has('code') }"
                       class="other-input" name="code">
                <div class="error-info-container" v-show="errors.has('code')||errorBag.has('code')">
                    <span class="form-text" v-show="errors.has('code')">{{errors.first('code')}}</span>
                    <span class="form-text" v-if="mismatchError('code')">{{errorBag.first('code')}}</span>
                </div>
                <button class="btn send-button" @click="submitPhone" type="button">绑定手机</button>
            </div>
            <div class="item-container">
                <div class="account-title">
                    <img src="./../../../image/password.svg" alt="" class="title-image">
                    <div class="title">修改密码</div>
                </div>

                <div class="title-container">
                    输入密码 :
                </div>
                <input class="other-input" v-model="password" v-validate="'required|min:9'" data-vv-as="密码"
                       id="password" :class="{'error-input':errors.has('password')||errorBag.has('password')}"
                       ref="password" type="password" name="password">
                <div class="error-info-container" v-show="errors.has('password')">
                    <span class="form-text">{{errors.first('password')}}</span>
                </div>
                <div class="title-container">
                    确认密码 :
                </div>
                <input v-model="confirmPassword" v-validate="'required|confirmed:password'" data-vv-as="密码"
                       id="confirmPassword" :class="{'error-input' : errors.has('confirmPassword') }"
                       type="password" class="other-input" name="confirmPassword">
                <div class="error-info-container" v-show="errors.has('confirmPassword')">
                    <span class="form-text">{{errors.first('confirmPassword')}}</span>
                </div>
                <verify @success="verifySuccess('password')" @error="verifyFailed('password')" :type="3"
                        :showButton="false" class="verify" :barSize="{width:'60%',height:'40px'}"></verify>
                <div v-show="errorBag.has('verifyPassword') ||errorBag.has('otherError')"
                     class="error-info-container">
                    <span class="form-text-left">{{errorBag.first('verifyPassword')}}</span>
                    <span class="form-text-left">{{errorBag.first('otherError')}}</span>
                </div>
                <button class="btn send-button" @click="submitPassword" type="button">应用修改</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import Verify from 'vue2-verify'
    import {ErrorBag} from 'vee-validate';
    import {Validator} from 'vee-validate';

    Validator.extend('phone', {
        getMessage: field => field + '格式错误',
        validate: value => {
            return value.length === 11 && /^((13|14|15|17|18|19)[0-9]{1}\d{8})$/.test(value)
        }
    });

    export default {
        name: "user-account",
        computed: {
            ...mapState({
                userInfo: state => state.AuthUser
            }),
        },
        data() {
            return {
                password: '',
                confirmPassword: '',
                phone: '',
                code: '',
                countDownTime: 10,
                canClick: true,
                buttonContent: '发送验证码',
                verifyPassword: false,
                verifyPhone: false,
                loading: false,
                errorBag: new ErrorBag(),
            }
        },
        watch: {
            'phone': function () {
                this.errorBag.remove('hasPhone');
            },
            'code': function () {
                this.errorBag.remove('code');
            },
            'password': function () {
                this.errorBag.remove('otherError');
            },
        },
        components: {
            verify: Verify
        },
        methods: {
            submitPhone() {
                this.$validator.validate('code').then(result => {
                    if (result) {
                        let bindInfo = {
                            phone: this.phone,
                            code: this.code
                        };
                        this.loading = true;
                        this.$store.dispatch('bindPhone', bindInfo).then(res => {
                            this.$message({
                                message: '绑定手机成功',
                                type: 'success'
                            });
                            this.loading = false;
                        }).catch(error => {
                            this.errorBag.add({
                                field: 'code',
                                msg: '验证码不匹配',
                            });
                            this.$message({
                                message: '绑定手机失败，请重试',
                                type: 'error'
                            });
                            this.loading = false;
                        })
                    }
                })
            },
            submitPassword() {
                this.$validator.validate('password').then(result => {
                    if (result) {
                        if (!this.verifyPassword) {
                            this.errorBag.add({
                                field: 'verifyPassword',
                                msg: '请先滑动认证',
                            });
                            return
                        }
                        this.loading = true;
                        axios.post('api/users/password', this.password).then(res => {
                            this.$message({
                                message: '修改密码成功',
                                type: 'success'
                            });
                            this.loading = false;
                        }).catch(error => {
                            this.errorBag.add({
                                field: 'otherError',
                                msg: '修改密码失败，请稍后再试',
                            });
                            this.$message({
                                message: '修改密码失败，请稍后再试',
                                type: 'error'
                            });
                            this.loading = false;
                        })
                    }
                })
            },
            mismatchError(filed) {
                return this.errorBag.has(filed) && !this.errors.has(filed);
            },
            verifySuccess(result) {
                if (result === 'password') {
                    this.verifyPassword = true;
                    this.errorBag.remove('verifyPassword');
                } else if (result === 'phone') {
                    this.verifyPhone = true;
                    this.errorBag.remove('verifyPhone');
                }
            },
            verifyFailed(result) {
                if (result === 'password') {
                    this.verifyPassword = false;
                } else if (result === 'phone') {
                    this.verifyPhone = false;
                }
            },
            sendCodeCheck() {
                if (this.phone === '') {
                    this.errorBag.add({
                        field: 'hasPhone',
                        msg: '请先输入手机号码',
                    });
                    return false;
                } else if (!this.verifyPhone) {
                    this.errorBag.add({
                        field: 'verifyPhone',
                        msg: '请先滑动认证',
                    });
                    return false;
                }
                if (!this.canClick) {
                    return false;
                }
            },
            getCode() {
                let result = this.sendCodeCheck();
                if (!result) {
                    return
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
                axios.post('/api/bind/phone/code', phone).then(res => {
                    this.$message({
                        message: '验证码发送成功',
                        type: 'success'
                    });
                }).catch(error => {
                    this.$message({
                        message: '验证码发送失败，请稍后再试',
                        type: 'error'
                    });
                })
            }
        }
    }
</script>

<style scoped src="./user-account.css">

</style>
