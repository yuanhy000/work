<template>
    <div class="user-info-container">
        <!--            <img :src="this.userInfo.user_avatar" alt="" class="user-avatar">-->
        <!--            <upLoad class="files" :data="item"-->
        <!--                     typeArr="image/png,image/jpg,image/gif,image/jpeg" size="50000"></upLoad>-->
        <avatar :userAvatar="this.userInfo.user_avatar" typeArr="image/png,image/jpg,image/gif,image/jpeg"></avatar>

        <div class="user-base-info">
            <div class="base-item">
                <div class="click-container" ref="userSex" @click="chooseSex">
                    <div :class="this.userInfo.user_sex?'sex-title-man':'sex-title-woman'">性别：</div>
                    <img :src="this.userInfo.user_sex?'./../../../icons/man.png' : './../../../icons/woman.png'"
                         alt="" class="user-sex">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="user-age">{{this.userInfo.user_age?'11':this.computeAge}}</div>
            </div>
            <div class="base-item">
                <img v-if="this.userInfo.user_zodiac" :src="this.userInfo.user_zodiac.zodiac_icon"
                     alt="" class="user-sex">
            </div>
            <div class="base-item">
                <img v-if="this.userInfo.user_constellation" alt="" class="user-sex"
                     :src="this.userInfo.user_constellation.constellation_icon">
            </div>
            <div class="base-item">
                <div class="user-month">{{this.userMonth}}</div>
                <div class="user-day">{{this.userDay}}</div>
            </div>
            <div class="base-item">
                <div class="user-month">{{this.userInfo.user_blood_type}}</div>
            </div>
        </div>

        <transition name="fade" mode="out-in">
            <div class="select-list" ref="menu" v-show="displaySelect"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div class="item-top">个人中心</div>
                <div class="item-bottom">退出</div>
            </div>
        </transition>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import avatar from "./avatar";

    export default {
        name: "user-info",
        components: {
            upLoad: avatar
        },
        computed: {
            ...mapState({
                userInfo: state => state.AuthUser
            }),
            computeAge: function () {
                let birthYear = new Date(this.userInfo.user_birth).getFullYear();
                let currentYear = new Date().getFullYear();
                return currentYear - birthYear;
            },
            userMonth: function () {
                return new Date(this.userInfo.user_birth).getUTCMonth() + 1;
            },
            userDay: function () {
                return new Date(this.userInfo.user_birth).getDate();
            },
        },
        mounted: function () {
            document.addEventListener("click", this.clickSelect);
        },
        beforeDestroy() {
            document.removeEventListener("click", this.clickSelect);
        },
        data() {
            return {
                displaySelect: false,
                selectWidth: 0,
                selectRight: 0,
                selectTop: 0,
            }
        },
        methods: {
            chooseSex() {
                console.log(this.$refs.userSex.getBoundingClientRect());
                let userSexDom = this.$refs.userSex.getBoundingClientRect();
                this.selectWidth = userSexDom.width;
                this.selectRight = document.body.clientWidth - userSexDom.right;
                this.selectTop = userSexDom.bottom - 70;
                this.displaySelect = !this.displaySelect;
            },
            clickSelect(e){
                console.log(!this.$refs.menu.contains(e.target));
                console.log(!this.$refs.userSex.contains(e.target));
                if (!this.$refs.menu.contains(e.target) && !this.$refs.userSex.contains(e.target)) {
                    this.displaySelect = false;
                }
            }
        }
    }
</script>

<style scoped src="./user-info.css">

</style>
