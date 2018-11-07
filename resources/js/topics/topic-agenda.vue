<template lang="pug">
    div
        .d-flex
            h3.mb-4 Topic Agenda
            .ml-auto
                a.btn.btn-default(:href="$route('admin.topics.new')") New Topic
        .row
            .col-4.mb-5(v-for="date of dates", :key="date")
                h5.text-muted.text-center {{ date | date('ddd, D MMM Y') }}
                .card.border-0.shadow-sm.mb-3(v-for="topic of groupedTopics[date]", :key="topic.id")
                    .card-body
                        .float-right
                            img.avatar.rounded-circle(:src="topic.speaker.avatar", v-if="topic.speaker && topic.speaker.avatar")
                        .lead {{ topic.name }}
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
            console.log('grouped topics', this.groupedTopics);
        },

        updateGroupedTopics() {
            const dates = this.topics.data.map(topic => topic.date);
            dates.forEach(date =>
                this.$set(this.groupedTopics, date, this.topics.data.filter(topic => topic.date === date))
            );
            console.log('update grouped topics', this.groupedTopics);
        }
    }
};
</script>
