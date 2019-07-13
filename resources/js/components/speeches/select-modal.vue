<template lang="pug">
    modal(ref="modal")
        h5(slot="title") Selecione um Discurso

        speech-card(v-for="speech of speeches", :key="speech.id",
            :speech="speech",
            :selected="isSelected(speech)",
            @click="select(speech)"
            selectable)

        .d-flex.justify-content-center.text-black-50(v-if="fetching")
            spinner(width="1rem")
                span.ml-2 Carregando...

        div.text-center(v-if="!fetching && !speeches.length")
            empty(title="Não há discursos para selecionar", style="max-width: 200px")

        .d-flex.w-100(slot="footer")
            button.btn.btn-default.mr-2(@click="create") Adicionar
            button.btn.btn-default.mr-2.ml-auto(@click="close") Cancelar
            button-loading.btn.btn-primary(@click="save", :loading="selected.submitting") Selecionar
</template>

<script>
import ModalComponent from '@/mixins/modal-component';
import SpeechCard from '@/components/speeches/speech-card';

export default {
    mixins: [ModalComponent],
    components: {
        SpeechCard,
    },

    data() {
        return {
            selected: new Form(),
            speeches: [],
            sunday: null,
            fetching: false,
            orders: [{ id: 1, name: '1º Orador' }, { id: 2, name: '2º Orador' }, { id: 3, name: '3º Orador' }],
        };
    },

    methods: {
        setup(sunday) {
            this.sunday = moment(sunday).format('YYYY-MM-DD');
            this.selected = new Form();
            this.fetchSpeeches();
            return this;
        },

        select(speech) {
            this.selected = new Form({ ...speech });
            this.selected.date = this.sunday;
            this.selected.duration = 15;
        },

        isSelected(speech) {
            return this.selected.id === speech.id;
        },

        create() {
            this.close();
            this.$emit('clickAdd', { date: this.sunday });
        },

        async fetchSpeeches() {
            try {
                this.fetching = true;
                const params = { per_page: 100, 'no-date': true };
                const { data: speeches } = await this.$axios.get(route('speeches.index'), { params });
                this.speeches = speeches.data;
            } finally {
                this.fetching = false;
            }
        },

        async save() {
            const { data: speech } = await this.selected.save(route('speeches.index'));
            this.$toasted.show('Discurso atualizado com sucesso');
            this.$emit('selected', speech);
            this.close();
        },
    },
};
</script>
