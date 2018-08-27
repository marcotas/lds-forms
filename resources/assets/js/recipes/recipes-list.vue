<template lang="pug">
    div
        h3.clearfix Receitas

        mt-table(
            ref="table",
            model-type="recipe",
            :columns="['id', 'name', 'description', 'status']",
            default-sort="name",
            :options="tableOptions")
            template(slot="top-actions")
                button.btn.btn-primary.float-right.text-gray-500
                    i.fa.fa-plus.mr-2
                    | Nova receita

            template(slot="status", slot-scope="prop")
                | {{ prop.row.status === 'active' ? 'Ativo' : 'Inativo' }}
</template>

<script>
export default {
    data() {
        return {
            page: 1,
            recipes: {
                data: [],
                links: null,
                meta: null,
            },
            removing: [],
            tableOptions: {
                headers: {
                    id: 'ID',
                    name: 'Nome',
                    description: 'Descrição',
                    status: 'Situação',
                },
                sortable: ['id', 'name'],
                filters: [
                    { field: 'status', label: 'Status Ativo', value: 'active' },
                    { field: 'status', label: 'Status Inativo', value: 'inactive' },
                ]
            }
        };
    },
}
</script>
