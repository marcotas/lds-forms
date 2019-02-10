<template lang="pug">
    .card.mb-4.border-0.shadow-sm

        .card-body
            h5.card-title Alterar Senha

            input-text.row(type="password", :form="passwordForm", field="current_password", label="Senha Atual", v-model="passwordForm.current_password", input-wrapper-class="col-md-6", label-class="col-md-3 col-form-label text-md-right")
            input-text.row(type="password", :form="passwordForm", field="password", label="Nova Senha", v-model="passwordForm.password", input-wrapper-class="col-md-6", label-class="col-md-3 col-form-label text-md-right")
            input-text.row(type="password", :form="passwordForm", field="password_confirmation", label="Confirmar Senha", v-model="passwordForm.password_confirmation", input-wrapper-class="col-md-6", label-class="col-md-3 col-form-label text-md-right")

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

