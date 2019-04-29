<template lang="pug">
    .card.mb-1(:class="{\
            'border border-warning t-bg-orange-lightest': !speech.author,\
            't-cursor-pointer hover_t-bg-grey-lightest': selectable,\
            't-border-blue t-bg-blue-lightest': selected,\
        }",
        @click="$emit('click', $event)")
        .card-body.p-3
            .card-title.m-0
                .float-right
                    button.btn.btn-default.btn-sm.rounded-circle(@click="$emit('remove', speech)",
                        v-if="!selectable"
                        v-tooltip="{title: 'Remover da importação', placement: 'left'}")
                        i.fa.fa-times
                    .d-flex.p-1.bg-primary.text-white.rounded-circle(v-if="selectable && selected")
                        i.fa.fa-check
            .d-flex.align-items-center
                img.mr-3.rounded(:src="speech.image_url", style="max-width: 100px")
                .mr-3
                    a.lead(:href="speech.link", :class="{'t-text-orange-dark': !speech.author}" target="_blank") {{ speech.title }}
                    .text-muted {{ speech.author }}
                    div(v-if="!speech.author")
                        span.t-text-center.t-bg-orange-light.t-text-white.px-2.t-rounded-full.t-uppercase Incompleto

</template>

<script>
export default {
    props: {
        speech: { required: true, type: Object },
        selectable: { default: false, type: Boolean },
        selected: { default: false, type: Boolean },
    },

    methods: {},
};
</script>
