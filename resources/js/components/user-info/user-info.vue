<template>
    <div class="user-info-container">
        <avatar :userAvatar="this.userInfo.user_avatar" typeArr="image/png,image/jpg,image/gif,image/jpeg"></avatar>
        <div class="user-base-info">
            <div class="base-item">
                <div class="click-container" ref="userSex" @click="chooseSex">
                    <div :class="this.userInfo.user_sex?'sex-title-man':'sex-title-woman'">性别：</div>
                    <img :src="this.userInfo.user_sex?'./../../../icons/man.png' : './../../../icons/woman.png'"
                         alt="" class="user-img">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userAge" @click="chooseAge">
                    <div class="age-title">年龄：</div>
                    <div class="user-age">{{this.userInfo.user_age?'11':this.computeAge}}</div>
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userZodiac" @click="chooseZodiac">
                    <div class="zodiac-title">生肖：</div>
                    <img v-if="this.userInfo.user_zodiac" :src="this.userInfo.user_zodiac.zodiac_icon"
                         alt="" class="user-img">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userConstellation" @click="chooseConstellation">
                    <div class="constellation-title">星座：</div>
                    <img v-if="this.userInfo.user_constellation" alt="" class="user-img"
                         :src="this.userInfo.user_constellation.constellation_icon">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userBirth" @click="chooseBirth">
                    <div class="birth-title">生日：</div>
                    <div class="birth-container">
                        <div class="user-month">{{this.userMonth}}</div>
                        <div class="user-day">{{this.userDay}}</div>
                    </div>
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="user-month">{{this.userInfo.user_blood_type}}</div>
            </div>
        </div>
        <transition name="fade" mode="out-in">
            <div class="select-sex" ref="sexMenu" v-show="displaySex"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div class="item-top">男</div>
                <div class="item-bottom">女</div>
            </div>
        </transition>
        <transition name="fade" mode="out-in">
            <div class="select-age" ref="ageMenu" v-show="displayAge"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div-scroll color="rgba(0,0,0,0.5)" size="5">
                    <div class="item-content" v-for="index of 100">{{index}}</div>
                </div-scroll>
            </div>
        </transition>
        <transition name="fade" mode="out-in">
            <div class="select-age" ref="zodiacMenu" v-show="displayZodiac"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div-scroll color="rgba(0,0,0,0.5)" size="5">
                    <div class="item-content" v-for="item in zodiac">{{item}}</div>
                </div-scroll>
            </div>
        </transition>
        <transition name="fade" mode="out-in">
            <div class="select-age" ref="constellationMenu" v-show="displayConstellation"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div-scroll color="rgba(0,0,0,0.5)" size="5">
                    <div class="item-content" v-for="item in constellation">{{item}}</div>
                </div-scroll>
            </div>
        </transition>
        <transition name="fade" mode="out-in">
            <div class="select-before" ref="birthMenu" v-show="displayBirth"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div class="year" @click="chooseYear">年</div>
                <div class="year" @click="chooseMonth">月</div>
                <div class="year" @click="chooseDay">日</div>
            </div>
        </transition>

        <transition name="fade" mode="out-in">
            <div class="select-age" ref="birthMenu" v-show="displayYear"
                 :style="{right:this.selectRight+'px',top:this.selectTop+'px',width:this.selectWidth+'px'}">
                <div-scroll color="rgba(0,0,0,0.5)" size="5">
                    <div class="item-content" v-for="item in 119" @click="selectYear">{{item+1900}}</div>
                </div-scroll>
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
                displaySex: false,
                displayAge: false,
                displayZodiac: false,
                displayConstellation: false,
                displayBirth: false,
                displayYear: false,
                selectWidth: 0,
                selectRight: 0,
                selectTop: 0,
                zodiac: ['鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪'],
                constellation: ['水瓶', '双鱼', '白羊', '金牛', '双子', '巨蟹',
                    '狮子', '处女', '天秤', '天蝎', '射手', '摩羯']
            }
        },
        methods: {
            chooseSex() {
                console.log(this.$refs.userSex.getBoundingClientRect());
                let userDom = this.$refs.userSex.getBoundingClientRect();
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
                this.displaySex = !this.displaySex;
            },
            chooseAge() {
                console.log(this.$refs.userAge.getBoundingClientRect());
                let userDom = this.$refs.userAge.getBoundingClientRect();
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
                this.displayAge = !this.displayAge;
            },
            chooseZodiac() {
                console.log(this.$refs.userZodiac.getBoundingClientRect());
                let userDom = this.$refs.userZodiac.getBoundingClientRect();
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
                this.displayZodiac = !this.displayZodiac;
            },
            chooseConstellation() {
                console.log(this.$refs.userConstellation.getBoundingClientRect());
                let userDom = this.$refs.userConstellation.getBoundingClientRect();
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
                this.displayConstellation = !this.displayConstellation;
            },
            chooseBirth() {
                console.log(this.$refs.userBirth.getBoundingClientRect());
                let userDom = this.$refs.userBirth.getBoundingClientRect();
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
                this.displayBirth = !this.displayBirth;
            },
            chooseYear() {
                console.log(this.$refs.userBirth.getBoundingClientRect());
                let userDom = this.$refs.userBirth.getBoundingClientRect();
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
                this.displayYear = !this.displayYear;
            },
            clickSelect(e) {
                if (!this.$refs.sexMenu.contains(e.target) && !this.$refs.userSex.contains(e.target)) {
                    this.displaySex = false;
                }
                if (!this.$refs.ageMenu.contains(e.target) && !this.$refs.userAge.contains(e.target)) {
                    this.displayAge = false;
                }
                if (!this.$refs.zodiacMenu.contains(e.target) && !this.$refs.userZodiac.contains(e.target)) {
                    this.displayZodiac = false;
                }
                if (!this.$refs.constellationMenu.contains(e.target) && !this.$refs.userConstellation.contains(e.target)) {
                    this.displayConstellation = false;
                }

            },
            selectYear() {
                this.displayYear = !this.displayYear;
            },
            selectMonth() {
                this.displayYear = !this.displayYear;
            },
            selectDay() {
                this.displayYear = !this.displayYear;
            },
        }
    }
</script>

<style scoped src="./user-info.css">

</style>
