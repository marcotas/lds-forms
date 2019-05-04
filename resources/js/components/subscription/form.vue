<template lang="pug">
    .container.my-4
        .row.justify-content-center
            .col-md-8
                .card.border-0.shadow-sm
                    .card-body
                        h5.card-title.mb-3 Inscrição

                        .form-group.row
                            label.form-label.col-md-4.text-md-right.mb-0.mt-2 Nome da Ala
                            .col-md-6
                                input-text.mb-0(:form="form", field="team_name", v-model="form.team_name")
                        .form-group.row
                            label.form-label.col-md-4.text-md-right.mb-0.mt-2 Nome
                            .col-md-6
                                input-text.mb-0(:form="form", field="name", v-model="form.name")
                        .form-group.row
                            label.form-label.col-md-4.text-md-right.mb-0.mt-2 E-mail
                            .col-md-6
                                input-text.mb-0(type="email", :form="form", field="email", v-model="form.email")
                        .form-group.row
                            label.form-label.col-md-4.text-md-right.mb-0.mt-2 Senha
                            .col-md-6
                                input-text.mb-0(type="password", :form="form", field="password", v-model="form.password")
                        .form-group.row
                            label.form-label.col-md-4.text-md-right.mb-0.mt-2 Confirmar Senha
                            .col-md-6
                                input-text.mb-0(type="password", :form="form", field="password_confirmation", v-model="form.password_confirmation")

                        .form-group.row.mb-0
                            .col-md-6.offset-md-4
                                button-loading.btn.btn-primary(@click.mt-2="subscribe" :loading="form.submitting") Inscrever-me
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
