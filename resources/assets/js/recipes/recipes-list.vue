<template lang="pug">
    div
        h3.clearfix Receitas

        mt-table(
            ref="table",
            model-type="recipe",
            :columns="['id', 'name', 'description', 'status']",
            default-sort="name",
            :options="tableOptions")
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
                    { name: 'status', label: 'Status Ativo', value: 'active' },
                    { name: 'status', label: 'Status Inativo', value: 'inactive' },
                ]
            }
        };
    },
}
</script>
