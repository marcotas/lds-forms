<template lang="pug">
    div
        .d-flex
            h3.mb-4 Topic Agenda
            .ml-auto
                a.btn.btn-primary(:href="$route('admin.topics.new')")
                    i.fa.fa-plus.mr-1
                    | New Topic
        .row
            .col-4.mb-5(v-for="date of dates", :key="date")
                h5.text-muted.text-center {{ date | date('ddd, D MMM Y') }}
                topic-card(v-for="topic of groupedTopics[date]", :key="topic.id"
                    :topic="topic"
                    @click="openTopicForm(topic)")
                //- .card.border-0.shadow-sm.mb-3.cursor-pointer.transitioned(
                    class="hover:shadow", v-for="topic of groupedTopics[date]", :key="topic.id"
                    @click="openTopicForm(topic)")
                    .card-body
                        .float-right.ml-2
                            img.avatar.rounded-circle(:src="topic.speaker.avatar.thumb", v-if="topic.speaker && topic.speaker.avatar")
                        .lead {{ topic.name }}
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
            const dates = Object.keys(this.groupedTopics);
            console.log('dates', dates);
            return dates;
        }
    },

    methods: {
        async fetchTopics(page = 1) {
            const params = { page, sort: 'date' };
            const { data: topics } = await this.$axios.get(this.$route('api.topics.index'), { params });
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
