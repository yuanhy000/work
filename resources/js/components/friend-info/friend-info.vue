<template>
    <div class="user-info-container">
        <loading v-show="loading" style="z-index:999;"></loading>
        <div class="user-head">
            <img :src="this.userInfo.user_avatar" alt="" class="user-avatar">
        </div>
        <div class="user-base-info">
            <div class="base-item">
                <div class="click-container">
                    <div :class="this.userInfo.user_sex?'sex-title-man':'sex-title-woman'">性别：</div>
                    <img :src="this.userInfo.user_sex?'./../../../icons/man.png' : './../../../icons/woman.png'"
                         alt="" class="user-img">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container">
                    <div class="age-title">年龄：</div>
                    <div class="user-age">{{this.userInfo.user_age?this.userInfo.user_age:this.computeAge}}</div>
                </div>
            </div>
            <div class="base-item">
                <div class="click-container">
                    <div class="zodiac-title">生肖：</div>
                    <img v-if="this.userInfo.user_zodiac" :src="this.userInfo.user_zodiac.zodiac_icon"
                         alt="" class="user-img">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container">
                    <div class="constellation-title">星座：</div>
                    <img v-if="this.userInfo.user_constellation" alt="" class="user-img"
                         :src="this.userInfo.user_constellation.constellation_icon">
                </div>
            </div>
            <div class="base-item">
                <div class="click-container">
                    <div class="birth-title">生日：</div>
                    <div class="birth-container">
                        <div class="user-month">{{this.userMonth}}</div>
                        <div class="user-day">{{this.userDay}}</div>
                    </div>
                </div>
            </div>
            <div class="base-item">
                <div class="click-container">
                    <div class="blood-title">血型：</div>
                    <div class="user-blood" v-model="userBlood">{{userBlood}}</div>
                </div>
            </div>
        </div>

        <div class="user-other-info">
            <div class="other-item">
                <div class="item-title">ID</div>
                <input class="item-container" :value="this.userInfo.user_number " readonly>
            </div>
            <div class="other-item">
                <div class="item-title">昵称</div>
                <input class="item-container" :value="this.userInfo.user_name" readonly>
            </div>
            <div class="other-item">
                <div class="item-title">地区</div>
                <input class="item-container" :value="this.userInfo.user_address" readonly>

            </div>
            <div class="other-item">
                <div class="item-title">学校</div>
                <input class="item-container" :value="this.userInfo.user_school" readonly>
            </div>
            <div class="other-item">
                <div class="item-title">个性签名</div>
                <input class="item-container" :value="this.userInfo.user_signature" readonly>
            </div>
        </div>
        <div class="user-bottom">
            <button class="btn submit-button" v-if="isFriend" @click="addFriend">发送消息</button>
            <button class="btn submit-button" v-if="!isFriend" @click="addFriend">添加好友</button>
        </div>
    </div>
</template>

<script>
    import loading from './../loading/loading';

    export default {
        name: "friend-info",
        components: {
            loading: loading
        },
        mounted() {
            console.log('Friend.accept.' + this.userInfo.user_id);
            window.Echo.private('Friend.accept.' + this.userInfo.user_id)
                .listen('AddFriend', (e) => {
                    console.log(e);
                    console.log('private channel call');
                    // this.chats.push(e);
                    // console.log(this.chats);
                });
            //
            // console.log(this.group.chats);
            // this.chats = this.group.chats;
        },
        data() {
            return {
                userInfo: {},
                loading: false,
                userMonth: 1,
                userDay: 1,
                userBlood: '',
                isFriend: false
            }
        },
        created() {
            this.userInfo = this.$route.params.user;
            this.initData();
            axios.post('api/users/is_friend', this.userInfo.user_id).then(res => {
                this.isFriend = res.data.isFriend;
                console.log(res.data.isFriend);
            })
        },
        methods: {
            addFriend() {
                console.log('123')
                axios.post('api/friends/add', this.userInfo.user_id).then(res => {
                    // this.isFriend = res.data.isFriend;
                    console.log(res);
                })
            },
            initData() {
                this.userMonth = new Date(this.userInfo.user_birth).getUTCMonth() + 1;
                this.userDay = new Date(this.userInfo.user_birth).getDate();
                this.userBlood = this.userInfo.user_blood_type;
            },
        }
    }
</script>

<style scoped src="./friend-info.css">

</style>
