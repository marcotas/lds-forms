<template lang="pug">
    section
        h3.clearfix Topics

        .row
            .col-md-4
                input-text(
                    icon="fa fa-search",
                    placeholder="Pesquisar...",
                    input-class="shadow-sm border-0",
                    v-model="search")
            .col-md-8
                .d-flex
                    button.btn.btn-default.ml-auto.text-gray-500.shadow-sm(@click="distribute")
                        i.fa.fa-code-branch.mr-2
                        | Distribute Topics
                    button.btn.btn-primary.ml-2.text-gray-500.shadow-sm
                        i.fa.fa-plus.mr-2
                        | New Topic

        mt-table(
            ref="table",
            :url="$route('api.topics.index')",
            :search="search",
            :columns="['id', 'name', 'date', 'speaker']",
            default-sort="date",
            :options="tableOptions")
            template(slot="name", slot-scope="{ row: topic }")
                a(:href="topic.link", target="_blank") {{ topic.name }}
            template(slot="date", slot-scope="{ row: topic }")
                | {{ topic.date | date('DD MMMM Y') }}
            template(slot="speaker", slot-scope="{ row: topic }")
                div(v-if="topic.speaker")
                    img.rounded-circle.avatar(:src="topic.speaker.avatar", v-if="topic.speaker.avatar")
                    span.ml-2 {{ topic.speaker.name }}

        confirmation(ref="confirmation")
</template>

<script>
export default {
    data() {
        return {
            search: null,
            page: 1,
            recipes: {
                data: [],
                links: null,
                meta: null
            },
            removing: [],
            tableOptions: {
                headers: {
                    id: 'ID',
                    name: 'Nome',
                    link: 'Link',
                    date: 'Data',
                    speaker: 'Discursante'
                },
                // avatars: ['speaker.avatar'],
                // avatars: ['speaker.avatar'],
                sortable: ['id', 'name', 'date']
                // filters: [
                //     { header: 'Situação' },
                //     { field: 'active', label: 'Ativos', value: true },
                //     { field: 'active', label: 'Inativos', value: false },
                //     { header: 'Sexo' },
                //     { field: 'gender', label: 'Homens', value: 'male' },
                //     { field: 'gender', label: 'Mulheres', value: 'female' },
                //     { header: 'Lixeira' },
                //     { field: 'with_trashed', label: 'Mostrar excluídos', value: true },
                //     { field: 'only_trashed', label: 'Mostrar Somente excluídos', value: true }
                // ]
            }
        };
    },

    created() {
        console.log('route', this.$route('api.topics.index'));
    },

    methods: {
        async action(selected) {
            return new Promise(resolve =>
                setTimeout(() => {
                    console.log('ação realizada para os ids', selected);
                    resolve();
                }, 1000)
            );
        },
        async distribute() {
            this.$axios.post(this.$route('api.topics.distribute'));
            this.$refs.table.refresh();
        }
    }
};
</script>
