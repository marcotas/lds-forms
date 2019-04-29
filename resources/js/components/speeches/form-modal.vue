<template lang="pug">
    modal(ref="modal")
        h5(slot="title") Discurso
        input-text(v-model="form.title", field="title", :form="form", label="Título")
        label
            span Link
            a.ml-2(:href="form.link", target="_blank", v-if="form.link", v-tooltip="{title: 'Abrir Link', placement: 'right'}")
                i.fa.fa-external-link-alt
        input-text(v-model="form.link", field="link", :form="form")
        input-text(type="date", v-model="form.date", field="date", :form="form", label="Data")

        input-select-2(label="Discursante",
            placeholder="Escolha um discursante",
            image-as="photo_url",
            :force-image="true",
            v-model="form.speaker_id",
            :options="speakers"
            :url="$laroute('users.index')")

        input-select(v-model="form.order", field="order", :form="form", label="Ordem", :options="orders")
        label Duração
            small.text-muted.ml-1 (minutos)
        input-text(type="number", step="5", v-model="form.duration", field="duration", :form="form", min="5")

        .d-flex.w-100(slot="footer")
            button.btn.btn-default.mr-2(v-if="!form.id", @click="onSelectClick") Selecionar
            button.btn.btn-default.mr-2.ml-auto(@click="close") Cancelar
            button-loading.btn.btn-primary(@click="save", :loading="form.submitting") Salvar
</template>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    data() {
        return {
            form: new Form({
                date: new Date(),
            }),
            speakers: [],
            removing: false,
            orders: [{ id: 1, name: '1º Orador' }, { id: 2, name: '2º Orador' }, { id: 3, name: '3º Orador' }],
        };
    },

    methods: {
        setup(speech = {}) {
            this.form = new Form(speech);
            this.speakers = (speech.speaker && [speech.speaker]) || [];
            this.form.order = this.form.order || 1;
            this.form.duration = this.form.duration || 15;
            this.fetchSpeakers();
            return this;
        },

        onSelectClick() {
            this.close();
            this.$emit('clickSelect', this.form);
        },

        async fetchSpeakers() {
            try {
                const params = { per_page: 100 };
                const { data: users } = await this.$axios.get(route('users.index'), { params });
                this.speakers = users.data;
            } catch (error) {
                console.log('error', error);
            }
        },

        async save() {
            const { data: speech } = await this.form.save(route('speeches.store'));
            this.$toasted.show('Discurso salvo com sucesso');
            this.$emit('saved', speech);
            this.close();
        },
    },
};
</script>
