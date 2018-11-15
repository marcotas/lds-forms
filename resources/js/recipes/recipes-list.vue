<template lang="pug">
    section
        h3.clearfix Receitas

        mt-table(
            ref="table",
            model-type="recipe",
            :columns="['id', 'name', 'description', 'status']",
            default-sort="name",
            :options="tableOptions")
            template(slot="top-actions")
                button.btn.btn-primary.float-right.text-gray-500.shadow-sm
                    i.fa.fa-plus.mr-2
                    | Nova receita

            template(slot="status", slot-scope="prop")
                | {{ prop.row.status === 'active' ? 'Ativo' : 'Inativo' }}

        confirmation(ref="confirmation")
</template>

<script>
export default {
    data() {
        return {
            tableOptions: {
                headers: {
                    id: 'ID',
                    name: 'Nome',
                    description: 'Descrição',
                    status: 'Situação'
                },
                sortable: ['id', 'name'],
                filters: [
                    { field: 'status', label: 'Status Ativo', value: 'active' },
                    { field: 'status', label: 'Status Inativo', value: 'inactive' }
                ],
                actions: [
                    {
                        name: 'Activate Recipe',
                        callback: this.action.bind(this)
                    },
                    {
                        name: 'Reiniciar',
                        callback: this.action.bind(this),
                        confirmation: {
                            message: 'Tem certeza que deseja realizar esta ação?',
                            title: 'Confirmação'
                        }
                    }
                ]
            }
        };
    },

    methods: {
        async action(selected) {
            return new Promise(resolve =>
                setTimeout(() => {
                    resolve();
                }, 1000)
            );
        }
    }
};
</script>
