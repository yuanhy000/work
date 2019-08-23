<template>
</template>


<script>
    import jwt from "../../helpers/jwt";

    export default {
        name: "auth-callback",
        beforeCreate() {
            jwt.setToken(this.$route.query.access_token);
            jwt.setAuthID(this.$route.query.auth_id);
            this.$router.push({name: 'home'});
        }
    }

</script>

<style scoped>
</style>
