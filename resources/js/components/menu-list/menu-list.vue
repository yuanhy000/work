<template>
    <transition name="fade" mode="out-in">
        <div :class="row ? 'row-menu' : 'select-menu'" v-show="display"
             :style="{right:this.right+'px', top:this.top+'px',
             height:this.height+'px', width:this.width+'px'}">
            <div-scroll color="rgba(0,0,0,0.5)" size="5">
                <div :class="row ? 'row-item' : 'item-content'" v-for="(item,index) in data"
                     @click="getClickItem(index)">{{item}}
                </div>
            </div-scroll>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            display: false,
            right: 0,
            top: 0,
            width: 0,
            height: 0,
            data: '',
            name: '',
            row: false,
        },
        methods: {
            getClickItem(index) {
                this.$emit('index', index);
            },
        },
    }
</script>

<style scoped src="./menu-list.css">

</style>
