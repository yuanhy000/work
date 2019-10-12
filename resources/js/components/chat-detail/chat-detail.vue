<template>
    <div class="chat-detail-container">
        <div class="chat-user-container">

        </div>
        <div class="chat-content-container">

        </div>
        <div class="chat-input-container">

        </div>
    </div>
</template>

<script>
    export default {
        name: "chat-detail",
        data() {
            return {
                friendInfo: [],

            }
        },
        created() {
            let friend_id = this.$route.params.friend_id;
            axios.post('api/chats/friend', friend_id).then(res => {
                console.log(res);
            })
        }
    }
</script>

<style scoped src="./chat-detail.css">

</style>
