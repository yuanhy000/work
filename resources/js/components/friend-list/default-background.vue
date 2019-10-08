<template>
    <div class="default-container">
        <img src="./../../../image/search-default2.png" alt="" class="search-default-img">
    </div>
</template>

<script>
    export default {
        name: "default-background"
    }
</script>

<style scoped>
    .default-container {
        background-color: #EDF1F8;
        width: 80%;
        position: absolute;
        top: 70px;
        bottom: 0;
        right: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .search-default-img {
        width: 250px;
        height: 250px;
        /*position: relative;*/
        /*left: 20%;*/
        /*right: 20%;*/
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        position: relative;
        margin: auto;
    }
</style>
