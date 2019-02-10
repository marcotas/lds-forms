<template lang="pug">
    .card.bg-light.mt-2
        .card-body.py-1.px-2.d-flex.align-items-center
            .d-flex.align-items-center.flex-wrap.mr-3
                p.card-title.mb-0.mr-3
                    span {{ permission.label }}
            i.fa.fa-fw.fa-times.ml-auto.text-muted.cursor-pointer(@click="$emit('remove', permission)")
</template>

<script>
export default {
    props: {
        permission: { required: true, type: Object },
    },
};
</script>
