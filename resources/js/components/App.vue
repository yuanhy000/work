<template>
    <div>
        <top-menu></top-menu>
        <transition name="fade" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    import TopMenu from './top-menu/top-menu'

    export default {
        components: {
            TopMenu,
        },
        mounted() {
            window.addEventListener('beforeunload', e => this.beforeunloadHandler(e))
            window.addEventListener('unload', e => this.unloadHandler(e))
        },
        destroyed() {
            window.removeEventListener('beforeunload', e => this.beforeunloadHandler(e))
            window.removeEventListener('unload', e => this.unloadHandler(e))
        },
        methods: {
            beforeunloadHandler() {
                this._beforeUnload_time = new Date().getTime();
            },
            unloadHandler(e) {
                this._gap_time = new Date().getTime() - this._beforeUnload_time;
            debugger
                if (this._gap_time <= 5) {
                    axios.post('api/users/offline');
                }
            },
        }
    }
</script>
<style>
    .btn:focus,
    .btn:active:focus,
    .btn.active:focus,
    .btn.focus,
    .btn:active.focus,
    .btn.active.focus {
        outline: none;
        box-shadow: none;
    }

    .distpicker-address-wrapper select {

        border: 2px solid #DBE2EC;
        outline: none;

    }

    .verify-move-block {
        background-color: #8EA7C7 !important;
        border-radius: 4px !important;
    }

    .verify-move-block:hover {
        background-color: #788FAF !important;
        transition-duration: 0.3s;
    }

    .icon-right:before {
        background-image: url("./../../image/right.svg");
        width: 25px;
        height: 25px;
    }

    .icon-check:before {

        background-image: url("./../../image/correct.svg");
        width: 25px;
        height: 25px;
    }

    .icon-close:before {

        background-image: url("./../../image/false.svg");
        width: 25px;
        height: 25px;
    }

    .verify-left-bar {
        border-radius: 4px !important;
        border-color: #8EA7C7 !important;
    }

    .verify-msg {
        color: #777;
    }

    .fade-enter-active {
        transition: all 0.2s;
        /*transition-delay: 0.8s;*/
    }

    .fade-leave-active {
        transition: all 0.2s;
    }

    .fade-enter, .fade-leave-active {
        opacity: 0;
    }
</style>
