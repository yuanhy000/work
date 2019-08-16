<template>
    <form class="login-form" @submit.prevent="login">
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
                   id="password" :class="{'error-input' : errors.has('password') }"
                   type="password" class="form-control login-email-input" name="password" required>
        </div>
        <div class="login-error-info-container" v-show="errors.has('password')">
            <label class="login-emil-name"></label>
            <span class="form-text">{{errors.first('password')}}</span>
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
                email: '',
                password: ''
            }
        },
        methods: {
            login() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        let loginInfo = {
                            email: this.email,
                            password: this.password,
                            type: 'email'
                        };
                        console.log(loginInfo);
                        return axios.post('/api/login', loginInfo).then(res => {
                            console.log(res)
                        })
                    }
                });
            }
        }
    }
</script>

<style scoped src="./login.css">

</style>
