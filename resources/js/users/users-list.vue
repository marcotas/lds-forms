<template lang="pug">
    section
        h3.clearfix Users

        .row
            .col-md-4
                input-text(
                    icon="fa fa-search",
                    placeholder="Pesquisar...",
                    input-class="shadow-sm border-0",
                    v-model="search")
            .col-md-8
                a.btn.btn-primary.float-right.text-gray-500.shadow-sm(:href="$route('admin.users.new')")
                    i.fa.fa-plus.mr-2
                    | New User

        mt-table(
            ref="table",
            model-type="user",
            :url="$route('api.users.index')"
            :search="search"
            :columns="['id', 'avatar.thumb', 'name', 'email', 'gender', 'active']",
            default-sort="-id",
            :options="tableOptions")
            template(slot="active", slot-scope="prop")
                | {{ prop.row.active ? 'Ativo' : 'Inativo' }}
            template(slot="gender", slot-scope="prop")
                | {{ prop.row.gender === 'male' ? 'Homem' : 'Mulher' }}

            template(slot="actions", slot-scope="{ row: user }")
                a.btn.btn-sm.bg-transparent(:href="$route('admin.users.edit', { user: user.id })")
                    i.far.fa-edit.text-black-50

        confirmation(ref="confirmation")
</template>

<script>
export default {
    data() {
        return {
            page: 1,
            search: null,
            recipes: {
                data: [],
                links: null,
                meta: null
            },
            removing: [],
            tableOptions: {
                headers: {
                    id: 'ID',
                    'avatar.thumb': '',
                    name: 'Nome',
                    gender: 'Sexo',
                    active: 'Situação'
                },
                avatars: ['avatar.thumb'],
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
                    { field: 'only_trashed', label: 'Mostrar Somente excluídos', value: true }
                ]
            }
        };
    },

    methods: {
        async action(selected) {
            return new Promise(resolve =>
                setTimeout(() => {
                    console.log('ação realizada para os ids', selected);
                    resolve();
                }, 1000)
            );
        }
    }
};
</script>
