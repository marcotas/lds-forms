<template lang="pug">
    .card
        .card-body.bg-light(:class="{ 'border-bottom': entries.length }")
            h4.mb-0 {{ title }}
                small.ml-2.text-primary(v-if="entries.length") {{ entries.length }} item(s)
                button.btn.float-right.btn-sm.btn-primary(@click="addEntry")
                    i.fa.fa-plus
                    |  Adicionar
        table.table.mb-0.table-bordered(v-if="entries.length")
            thead
                tr
                    th.text-center(v-for="column of columns", :style="column.style")
                        | {{ column.name }}
                    th(style="width: 10%")
            tbody
                tr(v-for="(entry, index) of entries", :key="index")
                    editable(
                        ref="editable",
                        tag="td",
                        v-for="column of columns",
                        :key="column.name",
                        v-model="entry[column.name]",
                        @input="onChange")
                    td(:style="columns[index].style")
                        button.btn.btn-sm.btn-danger(@click="removeEntry(index)")
                            i.fa.fa-trash

</template>

<script>
export default {
    props: {
        columns: { required: true, type: Array },
        value: { default: null },
        title: { required: true, type: String },
    },
    data() {
        return {
            entries: [],
        };
    },
    watch: {
        value() { this.entries = this.value || [] },
    },
    mounted() {
        this.entries = this.value || [];
    },
    methods: {
        addEntry() {
            this.entries.push(this.createEntry());
        },
        createEntry() {
            let value = {};
            this.columns.forEach((column, index) => {
                value[column.name] = null;
            })
            return value;
        },
        removeEntry(index) {
            this.entries.splice(index, 1);
        },
        onChange() {
            this.$emit('input', this.entries);
        },
    },
};
</script>
