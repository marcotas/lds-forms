<template lang="pug">
    modal(ref="modal")
        span(slot="title") Cliente

        input-text(:form="form", field="name", placeholder="Nome", v-model="form.name", left-icon="fa fa-fw fa-user")
        input-text(:form="form", field="email", placeholder="E-mail", v-model="form.email", left-icon="fa fa-fw fa-envelope")
        input-text.mb-0(
            :form="form",
            field="phone",
            placeholder="Telefone",
            v-model="form.phone",
            v-mask="['(##) ####-####', '(##) #####-####']",
            left-icon="fa fa-fw fa-phone")

        div(slot="footer")
            button.btn.btn-default(@click="close") Cancelar
            button-loading.btn.btn-primary.ml-2(@click="save", :loading="form.submitting") Salvar
</template>

<style lang="sass">
.form-group.has-left-icon
    position: relative
    i.fa
        position: absolute
        left: 10px
        top: 11px
        opacity: 0.5
    .form-control
        padding-left: 36px


</style>


<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    props: {
        resource: { default: null },
    },

    data() {
        return {
            form: new Form(this.resource || {}, 'multipart'),
        };
    },

    methods: {
        setup(client = {}) {
            this.form = new Form(client);
            console.log('form.phone', this.form.phone);
        },

        async save() {
            const { data: client } = await this.form.save(this.$laroute('clients.index'));
            console.log('save method response', client);
            this.$emit('saved', client.data);
        },
    },
};
</script>
