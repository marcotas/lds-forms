<template lang="pug">
    .card.mb-4.border-0.shadow-sm

        .card-body
            h5.card-title Alterar Senha

            .row
                label.col-md-3.col-form-label.text-md-right Senha Atual
                .col-md-6
                    input-text(type="password", :form="passwordForm", field="current_password", v-model="passwordForm.current_password")
            .row
                label.col-md-3.col-form-label.text-md-right Nova Senha
                .col-md-6
                    input-text(type="password", :form="passwordForm", field="password", v-model="passwordForm.password")
            .row
                label.col-md-3.col-form-label.text-md-right Confirmar Senha
                .col-md-6
                    input-text(type="password", :form="passwordForm", field="password_confirmation", v-model="passwordForm.password_confirmation")


            .form-group.row
                .offset-md-3.col-md-6
                    button-loading.btn.btn-primary(@click="updatePassword", :loading="passwordForm.submitting")
                        i.far.fa-save.with-text.mr-2
                        | Atualizar Senha
</template>

<script>
export default {
    props: {
        user: { type: Object, required: true },
    },

    data() {
        return {
            passwordForm: new Form(),
        };
    },

    methods: {
        async updatePassword() {
            const { data: updated } = await this.passwordForm.put(this.$laroute('users.password-update', { user: this.user.id }));

            this.passwordForm.reset();
            this.$toasted.success('Senha alterada com sucesso!');
        },
    },
};
</script>
