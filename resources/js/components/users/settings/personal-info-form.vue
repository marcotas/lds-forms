<template lang="pug">
    .card.mb-4.border-0.shadow-sm

        .card-body
            h5.card-title.mb-3 Informações Pessoais

            .row.justify-content-center.mb-3
                select-file(accept="image/*", icon="fas fa-3x fa-camera", circled
                            @change="personalInfoForm.new_photo = $event",
                            :image="personalInfoForm.photo_url",
                            :error="personalInfoForm.errors.get('new_photo')",
                            @updatePersonalInfo:error="personalInfoForm.errors.clear('new_photo')")
            input-text.row(:form="personalInfoForm", field="name", label="Name", v-model="personalInfoForm.name", input-wrapper-class="col-md-6", label-class="col-md-3 col-form-label text-md-right")
            input-text.row(:form="personalInfoForm", field="email", label="E-mail", v-model="personalInfoForm.email", input-wrapper-class="col-md-6", label-class="col-md-3 col-form-label text-md-right")

            .form-group.row
                .offset-md-3.col-md-6
                    button-loading.btn.btn-primary(@click="updatePersonalInfo", :loading="personalInfoForm.submitting")
                        i.far.fa-save.with-text.mr-2
                        | Salvar
</template>

<script>
export default {
    props: {
        user: { type: Object, required: true },
    },

    data() {
        return {
            personalInfoForm: new Form(this.user, 'multipart'),
        };
    },

    methods: {
        async updatePersonalInfo() {
            const { data: updated } = await this.personalInfoForm.put(this.$laroute('users.personal-info-update', { user: this.user.id }));

            this.personalInfoForm = new Form(updated, 'multipart');
            this.$toasted.success('Informações pessoais atualizadas com sucesso!');
        },
    },
};
</script>
