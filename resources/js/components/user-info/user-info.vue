<template>
    <div class="user-info-container">
        <notification class="notification" v-show="notification"></notification>
        <avatar :userAvatar="this.userInfo.user_avatar" @url="changeAvatar"
                typeArr="image/png,image/jpg,image/gif,image/jpeg"></avatar>
        <div class="user-base-info">
            <div class="base-item">
                <div class="click-container" ref="userSex" @click="chooseMenu('userSex')">
                    <div :class="this.userInfo.user_sex?'sex-title-man':'sex-title-woman'">性别：</div>
                    <img :src="this.userInfo.user_sex?'./../../../icons/man.png' : './../../../icons/woman.png'"
                         alt="" class="user-img">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userAge" @click="chooseMenu('userAge')">
                    <div class="age-title">年龄：</div>
                    <div class="user-age">{{this.userInfo.user_age?this.userInfo.user_age:this.computeAge}}</div>
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userZodiac" @click="chooseMenu('userZodiac')">
                    <div class="zodiac-title">生肖：</div>
                    <img v-if="this.userInfo.user_zodiac" :src="this.userInfo.user_zodiac.zodiac_icon"
                         alt="" class="user-img">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userConstellation" @click="chooseMenu('userConstellation')">
                    <div class="constellation-title">星座：</div>
                    <img v-if="this.userInfo.user_constellation" alt="" class="user-img"
                         :src="this.userInfo.user_constellation.constellation_icon">
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userBirth" @click="chooseMenu('userBirth')">
                    <div class="birth-title">生日：</div>
                    <div class="birth-container">
                        <div class="user-month">{{this.userMonth}}</div>
                        <div class="user-day">{{this.userDay}}</div>
                    </div>
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container" ref="userBlood" @click="chooseMenu('userBlood')">
                    <div class="blood-title">血型：</div>
                    <div class="user-blood" v-model="userBlood">{{userBlood}}</div>
                    <img src="./../../../image/more-color.svg" alt="" class="menu-icon">
                </div>
            </div>
        </div>

        <div class="user-other-info">
            <div class="other-item">
                <div class="item-title">昵称</div>
                <input class="item-container" @input="changeInput('name')" ref="inputName"
                       :value="this.userInfo.user_name">
            </div>
            <div class="other-item">
                <div class="item-title">地区</div>
                <v-distpicker class="item-distpicker" @selected="chooseAddress" :province="userAddress.province"
                              :city="userAddress.city" :area="userAddress.area">
                </v-distpicker>
            </div>
            <div class="other-item">
                <div class="item-title">故乡</div>
                <v-distpicker class="item-distpicker" @selected="chooseHometown" :province="userHometown.province"
                              :city="userHometown.city" :area="userHometown.area"></v-distpicker>
            </div>
            <div class="other-item">
                <div class="item-title">学校</div>
                <input class="item-container" @input="changeInput('school')" ref="inputSchool"
                       :value="this.userInfo.user_school">
            </div>
            <div class="other-item">
                <div class="item-title">个性签名</div>
                <input class="item-container" @input="changeInput('signature')" ref="inputSignature"
                       :value="this.userInfo.user_signature">
            </div>
        </div>

        <div class="user-bottom">
            <button class="btn submit-button" @click="commit">保存</button>
        </div>

        <menu-list :display="this.displaySex" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="sex" ref="sexMenu" :height=100 @index="changeSex">
        </menu-list>

        <menu-list :display="this.displayAge" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="100" ref="ageMenu" :height=250 @index="changeAge">
        </menu-list>

        <menu-list :display="this.displayZodiac" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="zodiac" ref="zodiacMenu" :height=250 @index="changeZodiac">
        </menu-list>

        <menu-list :display="this.displayConstellation" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="constellation" ref="constellationMenu" :height=250
                   @index="changeConstellation">
        </menu-list>

        <menu-list :display="this.displayBirth" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="dateOperation" ref="birthMenu" :height=50 :row=true
                   @index="chooseItem">
        </menu-list>

        <menu-list :display="this.displayYear" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="year" ref="yearMenu" :height=250 @index="changeYear">
        </menu-list>

        <menu-list :display="this.displayMonth" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="12" ref="monthMenu" :height=250 @index="changeMonth">
        </menu-list>

        <menu-list :display="this.displayDay" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="31" ref="dayMenu" :height=250 @index="changeDay">
        </menu-list>

        <menu-list :display="this.displayBlood" :top="this.selectTop" :right="this.selectRight"
                   :width="this.selectWidth" :data="blood" ref="bloodMenu" :height=200 @index="changeBlood">
        </menu-list>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import avatar from "./avatar";
    import loading from './../loading/loading';
    import notification from "../notification/notification";

    export default {
        name: "user-info",
        components: {
            upLoad: avatar,
            loading: loading,
            notification: notification
        },
        data() {
            return {
                userInfo: {},
                displaySex: false,
                displayAge: false,
                displayZodiac: false,
                displayConstellation: false,
                displayBirth: false,
                displayYear: false,
                displayMonth: false,
                displayDay: false,
                displayBlood: false,
                selectWidth: 0,
                selectRight: 0,
                selectTop: 0,
                sex: ['女', '男'],
                dateOperation: ['年', '月', '日'],
                zodiac: ['鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪'],
                constellation: ['水瓶', '双鱼', '白羊', '金牛', '双子', '巨蟹',
                    '狮子', '处女', '天秤', '天蝎', '射手', '摩羯'],
                blood: ['A', 'B', 'AB', 'O'],
                userMonth: 1,
                userDay: 1,
                userBlood: '',
                userName: '',
                zodiacLibrary: {},
                constellationLibrary: {},
                notification: false
            }
        },
        beforeCreate() {
            setTimeout(() => {
                this.userInfo = JSON.parse(JSON.stringify(this.$store.state.AuthUser));
                this.initData()
            }, 80);
            axios.get('/api/users/zodiac').then(res => {
                this.zodiacLibrary = res.data.data;
            });
            axios.get('/api/users/constellation').then(res => {
                this.constellationLibrary = res.data.data;
            })
        },
        computed: {
            year: function () {
                let year = [];
                for (let i = new Date().getFullYear(), index = 0; i >= 1900; i--, index++) {
                    year[index] = i;
                }
                return year;
            },
            computeAge: function () {
                let birthYear = new Date(this.userInfo.user_birth).getFullYear();
                let currentYear = new Date().getFullYear();
                return currentYear - birthYear;
            },
            userAddress: function () {
                let result = {};
                if (this.userInfo.user_address) {
                    let temp = this.userInfo.user_address.split("-");
                    result.province = temp[0];
                    result.city = temp[1];
                    result.area = temp[2];
                }
                return result;
            },
            userHometown: function () {
                let result = {};
                if (this.userInfo.user_hometown) {
                    let temp = this.userInfo.user_hometown.split("-");
                    result.province = temp[0];
                    result.city = temp[1];
                    result.area = temp[2];
                }
                return result;
            }
        },
        watch: {},
        mounted: function () {
            document.addEventListener("click", this.clickSelect);
        },
        beforeDestroy() {
            document.removeEventListener("click", this.clickSelect);
        },
        methods: {
            commit() {
                this.$store.dispatch('updateUser', this.userInfo).then(res => {
                    console.log('okkkk')
                    this.$store.dispatch('showNotification', {level: 'success', msg: '更新成功'});
                    this.notification = true;
                }).catch(error => {
                    this.$store.dispatch('showNotification', {level: 'danger', msg: '网络不稳定，请稍后再试'});
                    this.notification = true;
                })

            },
            initData() {
                this.userMonth = new Date(this.userInfo.user_birth).getUTCMonth() + 1;
                this.userDay = new Date(this.userInfo.user_birth).getDate();
                this.userBlood = this.userInfo.user_blood_type;
            },
            chooseAddress(res) {
                let province = res.province.value;
                let city = res.city.value;
                let area = res.area.value;
                this.userInfo.user_address = province + '-' + city + '-' + area;
                console.log('address: ' + this.userInfo.user_address);
            },
            chooseHometown(res) {
                let province = res.province.value;
                let city = res.city.value;
                let area = res.area.value;
                this.userInfo.user_hometown = province + '-' + city + '-' + area;
                console.log('hometown: ' + this.userInfo.user_hometown);
            },
            changeAvatar(url) {
                console.log(url);
                this.userInfo.user_avatar = url;
            },
            changeInput(type) {
                switch (type) {
                    case 'name':
                        this.userInfo.user_name = this.$refs['inputName'].value;
                        break;
                    case 'school':
                        this.userInfo.user_school = this.$refs['inputSchool'].value;
                        break;
                    case 'signature':
                        this.userInfo.user_signature = this.$refs['inputSignature'].value;
                        break;
                }
            },
            changeSex(index) {
                this.userInfo.user_sex = index;
                console.log(this.userInfo.user_sex)
                this.displaySex = false;
            },
            changeAge(index) {
                this.userInfo.user_age = index + 1;
                this.displayAge = false;
            },
            changeZodiac(index) {
                this.userInfo.user_zodiac = this.zodiacLibrary[index];
                console.log(this.userInfo.user_zodiac);
                this.displayZodiac = false;
            },
            changeConstellation(index) {
                this.userInfo.user_constellation = this.constellationLibrary[index];
                console.log(this.userInfo.user_constellation);
                this.displayConstellation = false;
            },
            changeBlood(index) {
                this.userBlood = this.blood[index];
                this.displayBlood = false;
                // this.userBlood.set(index);
            },
            changeYear(index) {
                let year = this.year[index];
                let temp = this.userInfo.user_birth.split("-");
                temp[0] = year;
                this.userInfo.user_birth = temp.join("-");
                this.displayYear = false;
            },
            changeMonth(index) {
                let temp = this.userInfo.user_birth.split("-");
                temp[1] = index + 1;
                this.userInfo.user_birth = temp.join("-");
                this.displayMonth = false;
                this.userMonth = index + 1;
            },
            changeDay(index) {
                let temp = this.userInfo.user_birth.split("-");
                temp[2] = index + 1;
                this.userInfo.user_birth = temp.join("-");
                this.displayDay = false;
                this.userDay = index + 1;
            },
            chooseItem(index) {
                switch (index) {
                    case 0:
                        this.displayYear = !this.displayYear;
                        break;
                    case 1:
                        this.displayMonth = !this.displayMonth;
                        break;
                    case 2:
                        this.displayDay = !this.displayDay;
                        break;
                }
            },
            chooseMenu(type) {
                let userDom = this.$refs[type].getBoundingClientRect();
                this.getPosition(userDom);
                switch (type) {
                    case 'userSex':
                        this.displaySex = !this.displaySex;
                        break;
                    case 'userAge':
                        this.displayAge = !this.displayAge;
                        break;
                    case 'userZodiac':
                        this.displayZodiac = !this.displayZodiac;
                        break;
                    case 'userConstellation':
                        this.displayConstellation = !this.displayConstellation;
                        break;
                    case 'userBirth':
                        this.displayBirth = !this.displayBirth;
                        break;
                    case 'userBlood':
                        this.displayBlood = !this.displayBlood;
                        break;
                }
            },
            clickSelect(e) {
                if (!this.$refs['sexMenu'].$el.contains(e.target) && !this.$refs['userSex'].contains(e.target)) {
                    this.displaySex = false;
                }
                if (!this.$refs['ageMenu'].$el.contains(e.target) && !this.$refs['userAge'].contains(e.target)) {
                    this.displayAge = false;
                }
                if (!this.$refs['zodiacMenu'].$el.contains(e.target) && !this.$refs['userZodiac'].contains(e.target)) {
                    this.displayZodiac = false;
                }
                if (!this.$refs['constellationMenu'].$el.contains(e.target) && !this.$refs['userConstellation'].contains(e.target)) {
                    this.displayConstellation = false;
                }
                if (!this.$refs['bloodMenu'].$el.contains(e.target) && !this.$refs['userBlood'].contains(e.target)) {
                    this.displayBlood = false;
                }
                if (!this.$refs['birthMenu'].$el.contains(e.target) && !this.$refs['yearMenu'].$el.contains(e.target) &&
                    !this.$refs['monthMenu'].$el.contains(e.target) && !this.$refs['dayMenu'].$el.contains(e.target) &&
                    !this.$refs['userBirth'].contains(e.target)) {
                    this.displayBirth = false;
                    this.displayYear = false;
                    this.displayMonth = false;
                    this.displayDay = false;
                }
            },
            selectYear() {
                this.displayYear = !this.displayYear;
            },
            selectMonth() {
                this.displayMonth = !this.displayMonth;
            },
            selectDay() {
                this.displayDay = !this.displayDay;
            },
            getPosition(userDom) {
                this.selectWidth = userDom.width;
                this.selectRight = document.body.clientWidth - userDom.right;
                this.selectTop = userDom.bottom - 70;
            }
        }
    }
</script>

<style scoped src="./user-info.css">

</style>
