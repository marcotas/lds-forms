<template lang="pug">
    .card.cursor-pointer.border-0.shadow-sm.mb-2(@click="$emit('click', speech)")
        .card-body.p-3
            avatar.float-right(v-if="speech.speaker", :username="speech.speaker.name", :size="46", v-tooltip="speech.speaker.name"
                @click.native.stop.prevent="() => null"
                :src="speech.speaker.photo_url")
            .lead
                span {{ speech.title }}
                a.ml-1(v-if="speech.link", :href="speech.link", target="_blank")
                    i.fa.fa-external-link-alt(style="font-size: .75rem")
            .d-flex.align-items-center.speech-small-text
                .text-muted.mr-2(v-if="speech.order") {{ speech.order }}º orador
                .text-muted.mr-2(v-if="speech.duration") {{ speech.duration }} minutos
                .text-info.text-uppercase(v-if="speech.invited_at && !speech.confirmed_at")
                    i.fa.fa-check
                    |  Convidado(a)
                .text-success.text-uppercase(v-if="speech.confirmed_at")
                    i.fa.fa-check-double
                    |  Confirmado(a)
</template>

<style lang="sass" scoped>
.speech-small-text
    font-size: .7rem
</style>


<script>
export default {
    props: {
        speech: { required: true, type: Object },
    },
};
</script>
