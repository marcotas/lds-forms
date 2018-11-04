<template lang="pug">
    .d-flex.align-items-center.justify-content.center.text-primary.divisor
        .w-100.border
        span.mx-3.d-flex.aling-items-center
            i(:class="{ [icon]: true, 'mr-2 my-auto': $slots.default }")
            span.mt-1(v-if="$slots.default")
                slot
        .w-100.border
</template>

<script>
export default {
    props: {
        icon: { default: 'fa fa-clock' },
    },
}
</script>
