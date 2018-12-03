<template lang="pug">
    section
        h3.clearfix Topics

        .row.mb-3
            .col-md-4
                input-text.mb-0.mt-2(
                    icon="fa fa-search",
                    placeholder="Pesquisar...",
                    input-class="shadow-sm border-0",
                    v-model="search")
            .col-md-8.mt-2
                .d-flex
                    button.btn.btn-default.ml-auto.text-gray-500.shadow-sm(@click="distribute")
                        i.fa.fa-code-branch.mr-2
                        | Distribute Topics
                    a.btn.btn-primary.ml-2.text-gray-500.shadow-sm(:href="$route('admin.topics.new')")
                        i.fa.fa-plus.mr-2
                        | New Topic

        resource-table(
            ref="table",
            :url="$route('api.topics.index')",
            :search="search",
            :columns="['id', 'name', 'date', 'created_at', 'speaker']",
            default-sort="-created_at",
            :options="tableOptions")
            template(slot="name", slot-scope="{ row: topic }")
                a(:href="$route('admin.topics.edit', { topic: topic.id })") {{ topic.name }}
                a(:href="topic.link", target="_blank")
                    i.fa.fa-external-link-alt.ml-2
            template(slot="date", slot-scope="{ row: topic }")
                | {{ topic.date | date('DD MMMM Y') }}
            template(slot="created_at", slot-scope="{ row: topic }")
                | {{ topic.created_at | date('DD MMMM Y') }}
            template(slot="speaker", slot-scope="{ row: topic }")
                div(v-if="topic.speaker")
                    img.rounded-circle.avatar(:src="topic.speaker.avatar.thumb", v-if="topic.speaker.avatar")
                    span.ml-2 {{ topic.speaker.name }}
            template(slot="actions", slot-scope="{ row: topic }")
                a.btn.btn-sm.bg-transparent(:href="$route('admin.topics.edit', { topic: topic.id })")
                    i.far.fa-edit.text-black-50

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
                    created_at: 'Criado Em',
                    speaker: 'Discursante'
                },
                sortable: ['id', 'name', 'date', 'created_at']
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
        },
        async distribute() {
            this.$axios.post(this.$route('api.topics.distribute'));
            this.$refs.table.refresh();
        }
    }
};
</script>
