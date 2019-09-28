<template>
    <div class="out-container">
        <div class="friend-list-container">
            <div-scroll color="rgba(0,0,0,0.5)" size="5">
                <router-link class="friend-add-container" to="/user-info" @click.native="selectTab(0)"
                             :class="selectIndex === 0 ? 'select': ''">
                    基本信息
                </router-link>
                <router-link class="friend-add-container" to="/user-account" @click.native="selectTab(1)"
                             :class="selectIndex === 1 ? 'select': ''">
                    账户信息
                </router-link>
                <router-link class="friend-add-container" to="/user-notification" @click.native="selectTab(2)"
                             :class="selectIndex === 2 ? 'select': ''">
                    消息通知
                </router-link>

            </div-scroll>
        </div>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "manage-wrapper",
        data() {
            return {
                selectIndex: 0,
            }
        },
        mounted() {
            let router = this.$route.path;
            switch (router) {
                case '/user-info':
                    this.selectIndex = 0;
                    break;
                case '/user-account':
                    this.selectIndex = 1;
                    break;
                case '/user-notification':
                    this.selectIndex = 2;
                    break;
            }
        },
        methods: {
            selectTab(index) {
                console.log(this.$route.path);
                this.selectIndex = index;
            }
        }
    }
</script>

<style scoped src="./user-manage.css">

</style>
