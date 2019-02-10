<template lang="pug">
    modal(ref="modal")
        span(slot="title") Serviço
        input-text(v-model="form.name", :form="form", field="name", label="Nome")
        input-text(v-model="form.commission", type="number", :form="form", field="commission", label="Comissão (em %)")
        input-text(v-model="form.price", type="number", :form="form", field="price", label="Preço (em reais)")
        input-textarea(v-model="form.description", :form="form", field="description", label="Descrição")

        div(slot="footer")
            button.btn.btn-default.mr-2(@click="close") Cancelar
            button-loading.btn.btn-primary(@click="save", :loading="form.submitting") Salvar
</template>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    props: {
        teamId: { required: true },
    },

    data() {
        return {
            form: new Form(),
        };
    },

    methods: {
        setup(service = {}) {
            this.form = new Form(service);
        },

        async save() {
            const { data: service } = await this.form.save(this.$laroute('services.index'));
            this.form = new Form(service.data);
            this.$emit('saved', service.data);
            this.close();
        },
    },
};
</script>
