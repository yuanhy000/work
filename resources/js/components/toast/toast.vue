<template>
    <div class="shadow-container">
        <div class="toast-container">
            <div class="title-container">
                <div class="toast-title">{{this.title}}</div>
            </div>
            <div class="content-container">
                <div class="toast-content">{{this.content}}</div>
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
        name: "toast",
        props: {
            title: '',
            content: ''
        },
        methods: {
            confirm() {
                this.$emit('option', true);
            },
            cancel() {
                this.$emit('option', false);
            }
        }
    }
</script>

<style scoped src="./toast.css">

</style>
