<template lang="pug">
    div
        h3.clearfix Receitas

        mt-table(
            ref="table",
            :url="$route('api.recipes.index')",
            :columns="['id', 'name', 'description', 'actions']",
            :options="{\
                headers: {\
                    id: 'ID',\
                    actions: '',\
                    name: 'Nome',\
                    description: 'Descrição'\
                }\
            }"
        )
            template(slot="actions", slot-scope="props")
                button-loading.btn.btn-sm.btn-danger(
                    :loading="isRemoving(props.row)",
                    @click="remove(props.row)")
                    i.fa.fa-trash(v-if="!isRemoving(props.row)")
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
        };
    },
    methods: {
        async remove(recipe) {
            this.removing.push(recipe.id);
            await http.delete(
                route('api.recipes.destroy', { recipe: recipe.id })
            );
            this.$toasted.show('Receita removida com sucesso', { singleton: true });
            this.$refs.table.refresh();
        },
        isRemoving(recipe) {
            return this.removing.includes(recipe.id);
        },
    },
}
</script>
