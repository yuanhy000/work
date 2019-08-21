<template>
    <form class="login-form" @submit.prevent="login">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="login-emil">
            <label for="email" class="login-emil-name">邮箱</label>
            <input v-model="email" v-validate="'required|email'" data-vv-as="邮箱"
                   id="email" :class="{'error-input' : errors.has('email') }"
                   class="form-control login-email-input" name="email" required>
        </div>
        <div class="login-error-info-container" v-show="errors.has('email')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('email')}}</span>
        </div>
        <div class="login-emil">
            <label for="password" class="login-emil-name">密码</label>
            <input v-model="password" v-validate="'required|min:9'" data-vv-as="密码"
                   id="password" :class="{'error-input' : errors.has('password')||errorBag.has('password') }"
                   type="password" class="form-control login-email-input" name="password" required>
        </div>
        <div class="login-error-info-container" v-show="errors.has('password') ||errorBag.has('password')">
            <label class="login-emil-name"></label>
            <span class="form-text" v-show="errors.has('password')">{{errors.first('password')}}</span>
            <span class="form-text" v-if="mismatchError">{{errorBag.first('password')}}</span>
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
                email: '',
                password: '',
                errorBag: new ErrorBag(),
                loading: false
            }
        },
        computed: {
            mismatchError() {
                return this.errorBag.has('password') && !this.errors.has('password')
            }
        },
        watch: {
            'password': function () {
                this.errorBag.remove('password');
            },
        },
        methods: {
            login() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.loading = true;
                        let loginInfo = {
                            email: this.email,
                            password: this.password,
                            type: 'email'
                        };
                        this.$store.dispatch('loginRequest', loginInfo).then(res => {
                            this.loading = false;
                            this.$router.push({name: 'home'});
                        }).catch(error => {
                            this.loading = false;
                            if (error.response.status === 404) {
                                this.errorBag.add({
                                    field: 'password',
                                    msg: error.response.data.message,
                                });
                            }
                        })
                    }
                });
            }
        }
    }
</script>

<style scoped src="./login.css">

</style>
