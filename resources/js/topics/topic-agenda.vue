<template lang="pug">
    div
        .d-flex
            h3.mb-4 Topic Agenda
            .ml-auto
                a.btn.btn-primary(:href="$route('admin.topics.new')")
                    i.fa.fa-plus.mr-1
                    | New Topic
        .row
            .col-lg-6.col-xl-4.mb-5(v-for="date of dates", :key="date")
                h5.text-muted.text-center {{ date | date('ddd, D MMM Y') }}
                topic-card(v-for="topic of groupedTopics[date]", :key="topic.id"
                    :topic="topic"
                    @click="openTopicForm(topic)")
        topic-form-modal(ref="topicModal", @updated="topicUpdated")
</template>

<script>
export default {
    data() {
        return {
            topics: {
                data: [],
                meta: null,
                links: null
            },
            groupedTopics: {}
        };
    },

    async created() {
        await this.fetchTopics();
    },

    computed: {
        dates() {
            return Object.keys(this.groupedTopics);
        }
    },

    methods: {
        async fetchTopics() {
            const params = { sort: 'date' };
            const { data: topics } = await this.$axios.get(this.$route('api.topics.agenda'), { params });
            this.topics = topics;
            this.updateGroupedTopics();
        },

        updateGroupedTopics() {
            const dates = this.topics.data.map(topic => topic.date);
            dates.forEach(date =>
                this.$set(this.groupedTopics, date, this.topics.data.filter(topic => topic.date === date))
            );
        },

        openTopicForm(topic) {
            this.$refs.topicModal.setup(topic).open();
        },

        topicUpdated(topic) {
            this.fetchTopics();
        }
    }
};
</script>
