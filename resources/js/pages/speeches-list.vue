<template lang="pug">
    .container-fluid.py-3
        h3 Discursos
            span(v-if="speeches.meta")  ({{ speeches.meta && speeches.meta.total }})

        input-text.my-4(v-model="search", input-class="rounded-pill border-0 shadow-sm",
            left-icon="fa fa-search",
            placeholder="Procure por título ou autor...")

        empty(style="width: 200px", v-if="!speeches.data.length", title="Nenhum discurso encontrado")

        div(v-if="speeches.data.length")
            .card.mb-2(v-for="speech of speeches.data", :key="speech.id")
                .card-body.p-3.d-flex.align-items-center
                    img.mr-3.rounded(:src="speech.image_url", v-if="speech.image_url", style="max-width: 75px")
                    .mr-3
                        a.lead(:href="speech.link", target="_blank") {{ speech.title }}
                        .text-muted {{ speech.author }}

            pagination(:meta="speeches.meta", :links="speeches.links", @page="fetchSpeeches")
</template>

<script>
import _ from 'lodash';

export default {
    props: {
        data: { default: () => [] },
    },

    data() {
        return {
            speeches: this.data,
            search: null,
        };
    },

    created() {
        const params = new URLSearchParams(window.location.search);
        if (params.has('search')) this.search = params.get('search');
    },

    watch: {
        search: _.debounce(function(search) {
            this.fetchSpeeches({ search });
        }, 500),
    },

    methods: {
        async fetchSpeeches(params = {}) {
            params.search = this.search;
            const { data: speeches } = await this.$axios.get(this.route('speeches.list'), { params });
            this.speeches = speeches;
        },
    },
};
</script>
