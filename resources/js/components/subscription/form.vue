<template lang="pug">
    .container.my-4
        .row.justify-content-center
            .col-md-8
                .card.border-0.shadow-sm
                    .card-body
                        h5.card-title.mb-3 Inscrição

                        input-text.row(:form="form", field="team_name", label="Nome da Ala", v-model="form.team_name", :input-wrapper-class="inputWrapperClass", :label-class="labelClass")
                        input-text.row(:form="form", field="name", label="Nome", v-model="form.name", :input-wrapper-class="inputWrapperClass", :label-class="labelClass")
                        input-text.row(type="email", :form="form", field="email", label="E-mail", v-model="form.email", :input-wrapper-class="inputWrapperClass", :label-class="labelClass")
                        input-text.row(type="password", :form="form", field="password", label="Senha", v-model="form.password", :input-wrapper-class="inputWrapperClass", :label-class="labelClass")
                        input-text.row(type="password", :form="form", field="password_confirmation", label="Confirmar Senha", v-model="form.password_confirmation", :input-wrapper-class="inputWrapperClass", :label-class="labelClass")

                        .form-group.row.mb-0
                            .col-md-6.offset-md-4
                                button-loading.btn.btn-primary(@click="subscribe" :loading="form.submitting") Inscrever-me
</template>

<script>
export default {
    data() {
        return {
            form: new Form({}),
            inputWrapperClass: 'col-md-6',
            labelClass: 'col-md-4 col-form-label text-md-right',
        };
    },

    methods: {
        async subscribe() {
            const { data: subscribed } = await this.form.post(this.$laroute('subscribe'));

            this.$toasted.success('Cadastrado com sucesso!');
            window.location.href = this.$laroute('home');
        },
    },
};
</script>
