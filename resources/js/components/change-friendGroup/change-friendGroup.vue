<template>
    <div class="shadow-container">
        <div class="toast-container">
            <div class="title-container">
                <div class="toast-title">{{title}}</div>
            </div>
            <div class="content-container">
                <input class="item-input" v-model="input" autofocus="autofocus">
            </div>
            <div class="operation-container">
                <button class="btn toast-button-left" @click="confirm">
                    确认
                </button>
                <button class="btn toast-button-right" @click="cancel">
                    取消
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "change-friendGroup",
        props: {
            title: '',
        },
        data() {
            return {
                input: '',
            }
        },
        methods: {
            confirm() {
                let data = {
                    option: true,
                    input: this.input
                };
                this.$emit('option', data);
                this.input = '';
            },
            cancel() {
                let data = {
                    option: false,
                    input: null
                };
                this.$emit('option', data);
            }
        }
    }
</script>

<style scoped src="./change-friendGroup.css">

</style>
