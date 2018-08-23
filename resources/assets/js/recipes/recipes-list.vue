<template lang="pug">
    div
        h3.clearfix Receitas

        mt-table(
            ref="table",
            model-type="recipe",
            :columns="['id', 'name', 'description']",
            default-sort="name",
            :options="tableOptions")
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
                },
                sortable: ['id', 'name']
            }
        };
    },
}
</script>
