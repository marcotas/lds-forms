<template lang="pug">
    .my-4
        h3.mb-4 Clientes
        .d-flex.mb-4
            input-text.mb-0.mr-auto(v-model='search', left-icon='fa fa-search', input-class='border-0 shadow-sm', placeholder="Procurar cliente...")
            button.btn.btn-primary.ml-auto(@click='addClient')
                i.fa.fa-plus
                span.d-none.d-sm-inline  Adicionar Cliente

        client-form(ref='form', @saved='saved')

        resource-table.border-0.shadow-sm(ref='table',
            url='/clients',
            :columns='columns',
            :search='search',
            :options='tableOptions',
            :can-edit='false',
            :can-show='false',
            default-sort='name')
            template(slot="more-actions", slot-scope="{ resource: client }")
                button.btn.btn-sm.bg-transparent(
                    v-if="!client.deleted_at"
                    @click="edit(client)",
                    v-tooltip="{title: 'Editar Cliente', boundary: 'window'}")
                    i.far.fa-edit.text-black-50

</template>

<script>
import ClientForm from './form';

export default {
    components: { ClientForm },

    data() {
        return {
            search: null,
            columns: ['id', 'name', 'email', 'phone'],
            tableOptions: {
                sortable: ['id', 'name', 'email', 'phone'],
                headers: {
                    name: 'Nome',
                    email: 'E-mail',
                    phone: 'Telefone',
                },
                filters: [
                    { label: 'Mostrar lixeira', field: 'withTrashed', value: true },
                    { label: 'Somente lixeira', field: 'onlyTrashed', value: true },
                ],
                messages: {
                    action_delete: 'Mover para lixeira',
                    action_force_delete: 'Excluir da lixeira',
                    action_restore: 'Restaurar',
                    action_select_all: 'Selecionar todos',
                    action_unselect_all: 'Deselecionar todos',
                    action_clear_selection: 'Limpar seleção',
                    action_delete_all: 'Mover selecionados para lixeira',
                    action_apply_filters: 'Aplicar Filtros',
                    label_selected: 'selecionado(s)',
                    confirmation: 'Confirmação',
                    confirmation_to_delete: 'Tem certeza que deseja mover este cliente para a lixeira?',
                    confirmation_to_bulk_delete: 'Tem certeza que deseja mover para a lixeira todos os items selecionados?',
                    confirmation_action_yes: 'Sim, Tenho Certeza',
                    confirmation_action_no: 'Cancelar',
                    confirmation_bulk_delete_yes: 'SIM, MOVER TODOS',
                    warning: 'Atenção',
                    confirmation_to_force_delete:
                        'Você tem certeza que deseja remover esses clientes permanentemente? Isso não poderá ser desfeito.',
                },
            },
        };
    },

    methods: {
        addClient() {
            this.$refs.form.open().setup();
        },

        edit(client) {
            this.$refs.form.open().setup(client);
        },

        saved(client) {
            this.$refs.form.close();
            this.$toasted.success(client.name + ' salvo com successo');
            this.$refs.table.refresh();
        },
    },
};
</script>
