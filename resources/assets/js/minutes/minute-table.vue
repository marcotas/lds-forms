<template lang="pug">
    div
        h4.text-primary(v-if="title") {{ title }}
        .row(v-if="entries.length")
            .col
                table.table.table-bordered.table-striped.table-sm
                    thead
                        tr
                            td(v-for="head of headers", :key="head") {{ head }}
                    tbody
                        tr(v-for="(entry, index) of entries", :key="index")
                            td(v-for="prop of Object.keys(entry)", :key="prop")
                                | {{ entry[prop] }}

</template>

<script>
export default {
    props: {
        title: { default: '', type: String },
        value: { default: () => ([]) },
    },
    data() {
        return {
            entries: [],
        };
    },
    computed: {
        headers() {
            if (!this.entries.length) return [];

            const entry = this.entries[0];
            return Object.keys(entry);
        },
    },
    watch: {
        value() { this.entries = this.value || [] },
    },
    mounted() {
        this.entries = this.value || [];
    },
};
</script>
