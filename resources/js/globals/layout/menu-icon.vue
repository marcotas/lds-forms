<template lang="pug">
  div.nav-bars(:class="{[type]: isActive}", @click="toggle")
    span(:class="backgroundClass")
    span(:class="backgroundClass")
    span(:class="backgroundClass")
</template>

<style lang="sass" scoped>
.nav-bars
    cursor: pointer
    width: 24px
    height: 24px
    position: relative
    span
        transition: all .3s ease-in-out
        position: absolute
        width: 24px
        height: 3px
        border-radius: 4px
        &:nth-child(1)
            top: 0px
        &:nth-child(2)
            top: 8px
        &:nth-child(3)
            top: 16px
.nav-bars.close
    float: initial
    span
        &:first-child
            top: 8px
            transform: rotate(225deg)
        &:nth-child(2)
            opacity: 0
        &:nth-child(3)
            top: 8px
            transform: rotate(-225deg)
</style>

<script>
export default {
    props: {
        active: { default: false },
        type: { default: 'close' },
        background: { default: 'bg-dark' },
        backgroundActive: { default: null },
    },

    model: {
        prop: 'active',
        event: 'change',
    },

    data() {
        return {
            isActive: this.active,
            activeBackground: this.backgroundActive || this.background,
        };
    },
    watch: {
        background() {
            this.activeBackground = this.backgroundActive || this.background;
        },
        active() {
            this.isActive = this.active;
        },
    },
    computed: {
        backgroundClass() {
            return this.isActive ? this.activeBackground : this.background;
        },
    },
    methods: {
        toggle() {
            this.isActive = !this.isActive;
            this.$emit('change', this.isActive);
        },
    },
};
</script>
