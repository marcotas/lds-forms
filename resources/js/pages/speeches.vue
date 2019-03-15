<template lang="pug">
    .container-fluid.py-3
        .d-flex.align-items-center.justify-content-center.mb-4
            button.btn.btn-secondary.btn-sm.mr-3.rounded-pill(v-tooltip="'Mês Anterior'", @click="prevMonth")
                i.fa.fa-arrow-left
            .text-center
                h3.mb-0 Discursantes
                h5.mb-0.text-muted.subtitle {{ today | date('MMMM YYYY') }}
            button.btn.btn-secondary.btn-sm.ml-3.rounded-pill(v-tooltip="'Próximo Mês'", @click="nextMonth")
                i.fa.fa-arrow-right

        .d-flex.justify-content-center.align-items-center.text-black-50(style="height: 100px", v-if="fetching")
            spinner(width="1rem", border-width="2px")
                span.ml-1.mt-1 Carregando...

        .row(v-if="!fetching")
            .col-lg-4.col-sm-6.col-xl-3(v-for="(sunday, index) of sundays", :key="index")
                .date.mb-4
                    h5.text-center {{ sunday | date }}
                    speech-card(v-for="speech of speechesOf(sunday)", :key="speech.id", :speech="speech"
                        @click="openSpeech")
                    .card.placeholder.d-flex.align-items-center.justify-content-center(v-if="speechesOf(sunday).length < 3")
                        div
                            a.text-primary(href="#", @click.prevent="addSpeetchAt(sunday)") Adicionar
                            |  ou
                            a.text-primary.ml-1(href="#", @click.prevent="selectSpeetchFor(sunday)") Selecionar

        speech-form-modal(ref="form", @saved="fetchSpeeches", @clickSelect="selectSpeetchFor($event.date)")
        speech-select-modal(ref="select", @selected="fetchSpeeches", @clickCreate="addSpeetchAt($event.date)")
</template>

<style lang="sass" scoped>
.date
    .card:not(.placeholder)
        &:hover
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important
            z-index: 1

    .card.placeholder
        height: 60px
        border-style: dashed
        border-width: 2px
        background: rgba(0, 0, 0, 0.025)
        transition: opacity .2s ease
        opacity: 0.5
        &:hover
            opacity: 1

</style>


<script>
import SpeechFormModal from '@/components/speeches/form-modal';
import SpeechSelectModal from '@/components/speeches/select-modal';
import SpeechCard from '@/components/speeches/card';

export default {
    components: { SpeechFormModal, SpeechSelectModal, SpeechCard },

    props: {
        data: { default: () => [] },
    },

    data() {
        return {
            speeches: this.data,
            today: moment(),
            fetching: false,
        };
    },

    computed: {
        sundays() {
            let sunday = this.today
                .clone()
                .startOf('month')
                .isoWeekday(7);
            const sundays = [];
            const month = sunday.month();

            while (month === sunday.month()) {
                sundays.push(sunday.clone());
                sunday.add(7, 'days');
            }

            return sundays;
        },
    },

    methods: {
        speechesOf(sunday) {
            return this.speeches
                .filter(speech => moment(speech.date).format('LL') === sunday.format('LL'))
                .sort((a, b) => a.order < b.order);
        },

        prevMonth() {
            this.today = this.today
                .clone()
                .startOf('month')
                .subtract(1, 'day');
            this.fetchSpeeches();
        },

        nextMonth() {
            this.today = this.today
                .clone()
                .endOf('month')
                .add(1, 'day');
            this.fetchSpeeches();
        },

        openSpeech(speech) {
            this.$refs.form.setup(speech).open();
        },

        addSpeetchAt(sunday) {
            console.log('add speech', sunday);
            this.$refs.form.setup({ date: moment(sunday).format('YYYY-MM-DD') }).open();
        },

        selectSpeetchFor(sunday) {
            this.$refs.select.setup(sunday).open();
        },

        async fetchSpeeches() {
            try {
                this.fetching = true;
                const params = {
                    date: this.today.format('YYYY-MM-DD'),
                };
                const { data: speeches } = await this.$axios.get(route('speeches.index'), { params });
                this.speeches = speeches.data;
            } finally {
                this.fetching = false;
            }
        },
    },
};
</script>
