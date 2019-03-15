<template lang="pug">
    modal(ref="modal")
        h5(slot="title") Selecione um Discurso

        .card.mb-2.cursor-pointer(v-if="!fetching", v-for="speech of speeches", :key="speech.id", @click="select(speech)",
            :class="{'border-primary': isSelected(speech)}")
            .card-body.py-2.px-3.d-flex.align-items-center
                span.mr-2 {{ speech.title }}
                i.fa.fa-check.ml-auto.text-primary(v-if="isSelected(speech)")

        .d-flex.justify-content-center.text-black-50(v-if="fetching")
            spinner(width="1rem")
                span.ml-2 Carregando...

        div.text-center(v-if="!fetching && !speeches.length")
            empty(title="Não há discursos para selecionar")

        .d-flex.w-100(slot="footer")
            button.btn.btn-default.mr-2(@click="create") Criar
            button.btn.btn-default.mr-2.ml-auto(@click="close") Cancelar
            button-loading.btn.btn-primary(@click="save", :loading="selected.submitting") Salvar
</template>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

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
        },

        isSelected(speech) {
            return this.selected.id === speech.id;
        },

        create() {
            this.close();
            // console.log('click create sunday', this.sunday);
            this.$emit('clickCreate', { date: this.sunday });
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
