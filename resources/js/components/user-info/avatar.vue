<template>
    <div class="user-head">
        <img :src="this.userAvatar" alt="" class="user-avatar" @click="choseImage">
        <input type="file" :accept="typeArr" @change="upload($event)" style="display:none" ref="input">
    </div>
</template>

<style scoped src="./user-info.css">
</style>

<script>
    import util from "../../helpers/util";

    export default {
        props: ['typeArr', 'size', 'userAvatar'],
        data() {
            return {
                client: '',
                fileSize: 5000000
            }
        },
        methods: {
            choseImage() {
                this.$refs.input.dispatchEvent(new MouseEvent('click'))
            },
            getRight() {
                if (this.size) {
                    this.fileSize = this.size;
                }
                let OSS = require('ali-oss');
                let client = new OSS({
                    region: '',
                    // secure: true,
                    accessKeyId: '',
                    accessKeySecret: '',
                    bucket: ''
                });
                this.client = client;
            },
            upload(event) {
                var self = this;
                var file = event.target.files[0];

                var type = file.name.split('.')[1];
                let nowTime = util.formatTime(new Date());
                var storeAs = 'avatar/' + nowTime + new Date().getTime() + '.' + type;
                var boolean = true;
                if (file.size > this.fileSize) {
                    console.log('亲,图片不能超过!' + this.fileSize / 1000 + 'kb');
                    return false;
                }
                if (this.typeArr && this.typeArr.indexOf(type) > 0) {
                    boolean = false;
                }
                if (boolean) {
                    console.log('上传图片格式不支持!请选择' + this.typeArr);
                    return false;
                }
                this.getRight();
                this.client.put(storeAs, file).then(function (result) {
                    let url = result.res.requestUrls[0].split('?')[0];
                    self.$emit('url', url);
                }).catch(function (err) {
                    console.log(err);
                });

            },
        }
    }
</script>
