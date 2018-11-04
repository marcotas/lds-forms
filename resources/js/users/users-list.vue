<template lang="pug">
    section
        h3.clearfix Usuários

        mt-table(
            ref="table",
            model-type="user",
            :columns="['id', 'avatar', 'name', 'email', 'gender', 'active']",
            default-sort="-id",
            :options="tableOptions")
            template(slot="top-actions")
                button.btn.btn-primary.float-right.text-gray-500.shadow-sm
                    i.fa.fa-plus.mr-2
                    | Novo Usuário

            template(slot="active", slot-scope="prop")
                | {{ prop.row.active ? 'Ativo' : 'Inativo' }}
            template(slot="gender", slot-scope="prop")
                | {{ prop.row.gender === 'male' ? 'Homem' : 'Mulher' }}

        confirmation(ref="confirmation")
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
                    avatar: '',
                    name: 'Nome',
                    gender: 'Sexo',
                    active: 'Situação',
                },
                avatars: ['avatar'],
                sortable: ['id', 'name', 'email'],
                filters: [
                    { header: 'Situação' },
                    { field: 'active', label: 'Ativos', value: true },
                    { field: 'active', label: 'Inativos', value: false },
                    { header: 'Sexo' },
                    { field: 'gender', label: 'Homens', value: 'male' },
                    { field: 'gender', label: 'Mulheres', value: 'female' },
                    { header: 'Lixeira' },
                    { field: 'with_trashed', label: 'Mostrar excluídos', value: true },
                    { field: 'only_trashed', label: 'Mostrar Somente excluídos', value: true },
                ],
            },
        };
    },

    methods: {
        async action(selected) {
            return new Promise(resolve =>
                setTimeout(() => {
                    console.log('ação realizada para os ids', selected);
                    resolve();
                }, 1000),
            );
        },
    },
};
</script>
