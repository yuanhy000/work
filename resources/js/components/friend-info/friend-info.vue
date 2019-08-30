<template>
    <div>123123123</div>
</template>

<script>
    export default {
        name: "friend-info",
        created() {
            console.log(this.$route.params.userId);
        }
    }
</script>

<style scoped src="./friend-info.css">

</style>
