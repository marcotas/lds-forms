<template>
    <span class="spinner d-flex align-items-center">
        <span class="spinner-border" role="status" :style="{width, height: width, borderWidth}">
            <span class="sr-only">Loading...</span>
        </span>
        <slot></slot>
    </span>
</template>

<style lang="scss">
.backdrop-spinner {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(246, 248, 249, 0.9);
}
</style>

<script>
export default {
    props: {
        width: { default: '2rem' },
        borderWidth: { default: '3px' },
    },
};
</script>
