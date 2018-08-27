<template lang="pug">
    div
        .row
            .col-md-4
                input-text(icon="fa fa-search", placeholder="Pesquisar...", input-class="shadow-sm border-0",
                    @input="onSearch",
                    v-model="search")
            .col-md-8
                button.btn.btn-primary.float-right.text-gray-500
                    i.fa.fa-plus.mr-2
                    | Nova receita

        .card.border-0.shadow-sm
            .card-header.bg-white(style="padding: 0.75rem")
                .d-flex.align-items-center
                    div
                        .custom-control.custom-checkbox.dropdown-toggle(data-toggle="dropdown")
                            input#bulk-checkbox.custom-control-input(type="checkbox", :checked="allSelected")
                            label.custom-control-label(for="bulk-checkbox")
                                span.text-hide Checkbox
                        .dropdown-menu
                            a.dropdown-item(href="#", @click.prevent="selectAll") Selecionar Todos
                            a.dropdown-item(href="#", @click.prevent="unselectAll") Deselecionar Todos
                            a.dropdown-item(href="#", @click.prevent="clearSelection") Limpar Seleção
                    .ml-3(v-if="selected.length") {{ selected.length }} selecionado(s)
                    .ml-auto.d-flex
                        //- FILTERS
                        button-dropdown(v-if="hasFilters",
                            :button-classes="buttonFilterClasses",
                            dropdown-classes="dropdown-menu-right")
                            span.mr-2(v-if="appliedFilters.length") ({{ appliedFilters.length }})
                            i.fa.fa-filter
                            div(slot="items")
                                a.dropdown-item(
                                    v-for="filter of filters", href="#", @click.prevent.stop="toggleFilter(filter)",
                                    :class="{active: isAppliedFilter(filter)}") {{ filter.label }}
                                //- a.dropdown-item.active(href="#", @click.prevent="unselectAll") Deselecionar Todos
                                //- a.dropdown-item(href="#", @click.prevent="clearSelection") Limpar Seleção

                        //- BULK DELETE OPTION
                        button-dropdown.ml-2(v-if="selected.length && hasBulkDelete",
                            button-classes="btn-sm btn-default",
                            dropdown-classes="dropdown-menu-right")
                            i.fa.fa-trash-alt.text-black-50
                            div(slot="items")
                                a.dropdown-item(href="#", @click.prevent="selectAll") Deletar Todos ({{ selected.length }})

            table.table.mb-0
                thead.bg-light
                    tr
                        th.text-secondary.py-1.border-top-0.border-bottom-0
                        th.text-secondary.py-1.text-uppercase.border-top-0.border-bottom-0(v-for="column of columns", :key="column")
                            .d-flex.align-items-center(
                                style="position: relative",
                                :class="{'is-sortable': isSortable(column)}",
                                @click="sortBy(column)"
                            )
                                span {{ head(column) }}
                                span.indicators.d-inline-flex.flex-column.pl-2(v-if="isSortable(column)")
                                    i.fa.fa-chevron-up(:class="{active: hasSorted(column)}")
                                    i.fa.fa-chevron-down(:class="{active: hasSortedReverse(column)}")
                        th.text-secondary.py-1.border-top-0.border-bottom-0(style="min-width: 130px")
                tbody
                    tr(v-for="resource of resources.data", :key="resource[trackBy]")
                        td
                            .custom-control.custom-checkbox
                                input.custom-control-input(
                                    type="checkbox",
                                    :id="checkboxId(resource)",
                                    @input="onCheck(resource)",
                                    :checked="isResourceSelected(resource)")
                                label.custom-control-label(:for="checkboxId(resource)")
                                    span.text-hide Checkbox
                        td(v-for="column of columns", :key="column")
                            slot(:row="resource", :name="column") {{ resource[column] }}
                        td
                            slot(:row="resource", name="actions")
                                .actions
                                    button.btn.btn-sm.bg-transparent
                                        i.far.fa-eye.text-black-50
                                    button.btn.btn-sm.bg-transparent
                                        i.far.fa-edit.text-black-50
                                    button.btn.btn-sm.bg-transparent(@click="confirmRemove(resource)")
                                        i.far.fa-trash-alt.text-black-50

            pagination.border-top.pt-3.pb-2(:meta="resources.meta", :links="resources.links", @page="fetchResources" v-if="hasPage")

        modal(ref="confirmDelete", :centered="true", effect="zoomin")
            span(slot="title") Confirmation
            .lead Are you sure you want to delete this record?
            div(slot="footer")
                button.btn.bg-transparent(type='button', data-dismiss='modal') Cancel
                button-loading.btn.btn-danger.ml-2(
                    type='button',
                    @click="remove(targetResource)",
                    :loading="isRemoving(targetResource)") Yes, I'm Sure
</template>

<style lang="sass" scoped>
@import '../../../sass/_variables.scss'
.is-sortable
    user-select: none

    span
        cursor: pointer

    .indicators
        font-size: .65rem
        i.fa
            opacity: 0.4
            &.active
                opacity: 1
                color: $primary

.modal.zoomin
    transform: scale(1.2)
    opacity: 0
    transition: all .2s ease
    &.show
        transform: scale(1)
        opacity: 1
        transition: all .2s ease

.table
    .actions
        i
            font-size: 1rem
</style>


<script>
export default {
    props: {
        /**
         * The columns fields to be displayed.
         *
         * Example: columns: ['id', 'name', 'created_at']
         */
        columns: {
            required: true,
            type: Array
        },

        /**
         * The ID of each object.
         */
        trackBy: {
            required: false,
            default: 'id',
            type: String,
        },

        /**
         * The model type name.
         */
        modelType: {
            required: true,
            type: String,
        },

        /**
         * The model type plural name.
         */
        modelTypePlural: {
            required: false,
            type: String,
        },

        /**
         * Table options.
         */
        options: {
            required: false,
            default: () => ({
                headers: {},
                sortable: [],
            })
        },

        /**
         * Initial sorted column.
         */
        defaultSort: {
            required: false,
            default: null,
        },

        /**
         * Message when a resource is deleted
         */
        deleteMessage: {
            default: 'Resource successfully deleted',
        },

        /**
         * Flag to display the bulk delete option.
         */
        hasBulkDelete: {
            default: true
        }
    },

    data() {
        return {
            resources: {
                data: [],
                links: null,
                meta: null,
            },
            page: 1,
            loading: false,
            search: null,
            selected: [],
            sort: null,
            removing: [],
            targetResource: null,
            appliedFilters: [],
        };
    },

    created() {
        this.sort = this.defaultSort;
        if (this.indexUrl) {
            this.fetchResources();
        }
        if (!this.options) {
            this.options = {};
        }
        if (!this.options.sortable) {
            this.options.sortable = [];
        }
    },

    computed: {
        hasPage() {
            return this.resources.meta && this.resources.meta.last_page > 1;
        },
        ids() {
            return this.resources.data.map(r => r[this.trackBy]);
        },
        allSelected() {
            return !this.ids.some(id => !this.selected.includes(id));
        },
        sortables() {
            return this.options.sortable;
        },
        modelPlural() { return this.modelTypePlural || this.modelType + 's' },
        indexUrl() { return this.$route(`api.${this.modelPlural}.index`); },
        destroyUrl() {
            return this.$route(`api.${this.modelPlural}.destroy`, {
                [this.modelType]: this.targetResource[this.trackBy]
            });
        },
        hasFilters() {
            return this.options.filters && this.options.filters.length;
        },
        filters() {
            return this.options.filters;
        },
        buttonFilterClasses() {
            return this.appliedFilters.length ? 'btn-sm btn-primary' : 'btn-sm btn-default';
        },
    },

    methods: {
        applyFilter(filter) {
            this.appliedFilters.push(filter);
        },

        toggleFilter(filter) {
            this.isAppliedFilter(filter) ?
                this.removeFilter(filter) :
                this.applyFilter(filter);
            this.refresh();
        },

        confirmRemove(resource) {
            this.targetResource = resource;
            this.$refs.confirmDelete.open();
        },

        async remove(resource) {
            this.removing.push(resource[this.trackBy]);
            await http.delete(this.destroyUrl);
            this.$toasted.show(this.deleteMessage, { singleton: true });
            this.targetResource = null;
            this.refresh();
            this.$refs.confirmDelete.close();
        },

        removeFilter(filter) {
            this.appliedFilters.splice(this.appliedFilters.indexOf(filter), 1);
        },

        isAppliedFilter(filter) {
            return this.appliedFilters.includes(filter);
        },

        isRemoving(resource) {
            return resource
                && this.removing.includes(resource[this.trackBy]);
        },

        async fetchResources(page = 1) {
            this.loading = true;
            const search = this.search || null;
            const sort = this.sort;
            this.page = search ? page : 1;
            const filter = this.getPreparedFilters();
            const { data: resources } = await http.get(this.indexUrl, { params: { page, search, sort, filter }});
            this.loading = false;
            this.resources = resources;
        },

        getPreparedFilters() {
            const filters = {};
            this.appliedFilters.forEach(filter => {
                if (filters[filter.name]) {
                    return filters[filter.name] += ',' + filter.value;
                }
                filters[filter.name] = filter.value;
            });
            return filters;
        },

        refresh() {
            this.fetchResources(this.page);
        },

        head(column) {
            let header = this.options.headers
                && this.options.headers[column]
            if (header !== undefined) {
                return header;
            }
            return column;
        },

        sortBy(column) {
            if (!this.isSortable(column)) return;

            if (this.hasSorted(column)) {
                this.sort = '-' + column;
            } else if (this.hasSortedReverse(column)) {
                this.sort = null;
            } else {
                this.sort = column;
            }

            this.refresh();
        },

        hasSorted(column) {
            return this.sort === column;
        },

        hasSortedReverse(column) {
            return this.sort === `-${column}`;
        },

        removeSort(column) {
            const index = this.sort.map(c => c.replace('-', '')).findIndex(c => c === column);

            if (index >= 0) {
                this.sort.splice(index, 1);
            }
        },

        isResourceSelected(resource) {
            const id = resource[this.trackBy];
            return this.selected.includes(id);
        },

        isSortable(column) {
            return this.sortables.includes(column);
        },

        selectAll() {
            this.ids.filter(id => !this.selected.includes(id))
                .forEach(id => this.selected.push(id));
        },

        unselectAll() {
            this.ids.filter(id => this.selected.includes(id))
                .forEach(id => this.selected.splice(this.selected.indexOf(id), 1));
        },

        clearSelection() {
            this.selected = [];
        },

        onCheck(resource) {
            const id = resource[this.trackBy];
            this.selected.includes(id) ?
                this.selected.splice(this.selected.indexOf(id), 1) :
                this.selected.push(id);
        },

        onSearch: _.debounce(function (text) {
            this.refresh();
        }, 500),

        checkboxId(resource) {
            return 'checkbox-' + resource[this.trackBy];
        },
    },
}
</script>
