<template lang="pug">
    div
        .row.mb-0
            .col-md-4.mb-3
                h3.mb-0 {{ this.resource ? 'Editar' : 'Novo' }} Usuário
            .col-md-8.mb-3
                .d-flex.align-items-center
                    a.btn.btn-sm.btn-secondary.ml-auto(:href="$laroute('users.index')") Voltar Pra Lista
                    a.btn.btn-sm.btn-primary.ml-2(:href="$laroute('users.create')", v-if="form.id") Novo Usuário

        div
            b-card.border-0.shadow-sm(no-body)
                b-tabs(card)
                    b-tab(title="Informações", title-link-class="white")
                        template(slot="title")
                            i.fa.fa-fw.fa-user-edit.mr-2
                            span Informações

                        input-text(:form="form", field="name", label="Nome", v-model="form.name")
                        input-text(:form="form", field="email", type="email" label="Email", v-model="form.email")
                        input-text(:form="form", field="password", label="Senha", type="password" v-model="form.password")
                        input-text(:form="form", field="password_confirmation", label="Confirmar Senha", type="password", v-model="form.password_confirmation")

                        button-loading.btn.btn-success(@click='update', v-if='form.id', :loading='form.submitting')
                            | Atualizar
                        button-loading.btn.btn-success(@click='create', v-if='!form.id', :loading='form.submitting')
                            | Criar

                    b-tab(title-link-class="white", active, v-if="form.id")
                        template(slot="title")
                            i.fa.fa-fw.fa-key.mr-2
                            span Permissões

                        permissions-and-roles(:all-permissions="permissions", :user="resource || {}")
</template>

<script>
import PermissionsAndRoles from '@/components/permissions/permissions-and-roles';

export default {
    components: { PermissionsAndRoles },

    props: {
        resource: { default: null },
        permissions: { required: true, type: Array },
    },

    data() {
        return {
            form: new Form(this.resource || {}),
            user: this.resource,
            userRoles: [],
        };
    },

    methods: {
        async create() {
            const { data: created } = await this.form.post(this.$laroute('users.store'));

            this.$toasted.success('Resource created');
            window.location.href = this.$laroute('users.edit', { user: created.data.id });
        },

        async update() {
            const { data: updated } = await this.form.put(
                this.$laroute('users.update', {
                    user: this.form.id,
                })
            );

            this.form = new Form(updated.data);
            this.$toasted.success('Resource updated');
            window.location.reload();
        },
    },
};
</script>
