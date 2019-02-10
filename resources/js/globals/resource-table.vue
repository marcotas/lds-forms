<template lang="pug">
    .card.resource-table
        .card-header.bg-white(style="padding: 0.75rem")
            .d-flex.align-items-center
                div(v-if="hasBulkActions")
                    .custom-control.custom-checkbox.dropdown-toggle(data-toggle="dropdown")
                        input#bulk-checkbox.custom-control-input(type="checkbox", :checked="allSelected")
                        label.custom-control-label(for="bulk-checkbox")
                            span.text-hide Checkbox
                    .dropdown-menu
                        a.dropdown-item(href="#", @click.prevent="selectAll") {{ messages.action_select_all }}
                        a.dropdown-item(href="#", @click.prevent="unselectAll") {{ messages.action_unselect_all }}
                        a.dropdown-item(href="#", @click.prevent="clearSelection") {{ messages.action_clear_selection }}

                .mx-3(v-if="selected.length") {{ selected.length }} {{ messages.label_selected }}

                h5.mb-0.mr-3(v-if="$slots.title")
                    slot(name="title")

                .ml-auto.d-flex
                    //- ACTIONS
                    .d-flex(v-if="hasActions && selected.length")
                        mt-select.mr-2(
                            button-class="btn-sm btn-default",
                            v-model="actionSelected",
                            track-by="name",
                            label="name",
                            :options="_actions")
                        button-loading.btn.btn-primary.btn-sm.mr-2(
                            :disabled="!actionSelected",
                            @click="performAction",
                            :loading="actionLoading")
                            i.fa.fa-play.fa-fwfw(v-if="!actionLoading")

                    //- FILTERS
                    button-dropdown(v-if="hasOption('filters')",
                        :button-classes="buttonFilterClasses",
                        v-tooltip="{title: messages.action_apply_filters, boundary: 'window', delay: {show: 1000, hide: 0} }"
                        dropdown-classes="dropdown-menu-right")
                        span.mr-2(v-if="appliedFilters.length") ({{ appliedFilters.length }})
                        i.fa.fa-filter(:class="{ 'text-black-50': !appliedFilters.length }")
                        div(slot="items")
                            .filter(v-for="filter of filters")
                                .bg-light.px-3.text-muted.text-uppercase(
                                    v-if="filter.header", @click.stop.prevent="() => {}")
                                    small {{ filter.header }}
                                a.dropdown-item(
                                    v-if="!filter.header"
                                    href="#", @click.prevent.stop="toggleFilter(filter)",
                                    :class="{active: isAppliedFilter(filter)}") {{ filter.label }}

                    //- BULK DELETE OPTION
                    button-dropdown.ml-2(
                        v-if="selected.length && hasBulkDelete"
                        button-classes="btn-sm btn-default",
                        dropdown-classes="dropdown-menu-right")
                        i.far.fa-trash-alt.text-black-50
                        div(slot="items")
                            a.dropdown-item(href="#", @click.prevent="confirmBulkDelete" v-if="canDelete") {{ messages.action_delete_all }} ({{ selected.length }})

        .table-responsive
            table.table.mb-0
                thead.bg-light
                    tr
                        th.text-secondary.py-1.border-top-0.border-bottom-0(v-if="hasBulkActions")
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
                    tr(valign="middle", v-for="resource of resources.data", :key="resource[trackBy]")
                        td.align-middle(v-if="hasBulkActions")
                            .custom-control.custom-checkbox
                                input.custom-control-input(
                                    type="checkbox",
                                    :id="checkboxId(resource)",
                                    @input="onCheck(resource)",
                                    :checked="isResourceSelected(resource)")
                                label.custom-control-label(:for="checkboxId(resource)")
                                    span.text-hide Checkbox
                        td.align-middle(v-for="column of columns", :key="column")
                            slot(:resource="resource", :name="column")
                                span(v-if="!isAvatar(column) && !isImage(column)") {{ objGet(resource, column) }}
                                img.avatar.rounded-circle(
                                    v-if="isAvatar(column)"
                                    :class="{ \
                                        [options.avatars[column] ? options.avatars[column]['cssClass'] || '' : '']: true \
                                    }"
                                    :src="objGet(resource, column)")
                                img(
                                    v-if="isImage(column)"
                                    :class="{ \
                                        [options.images[column] ? options.images[column]['cssClass'] || '' : 'resource-image']: true \
                                    }"
                                    :src="objGet(resource, column)")
                        td.align-middle
                            .actions
                                slot(:resource="resource", name="more-actions")
                                slot(:resource="resource", name="actions")
                                    a.btn.btn-sm.bg-transparent(:href="resourceUrl(resource)" v-if="canShow")
                                        i.far.fa-eye.text-black-50
                                    a.btn.btn-sm.bg-transparent(:href="editUrl(resource)" v-if="canEdit")
                                        i.far.fa-edit.text-black-50
                                button.btn.btn-sm.bg-transparent(
                                    @click="confirmRemove(resource)",
                                    v-tooltip="{title: messages.action_delete, boundary: 'window'}"
                                    v-if="!wasSoftDeleted(resource) && resourceUrl(resource) && canDelete")
                                    i.far.fa-trash-alt.text-black-50
                                button.btn.btn-sm.bg-transparent(
                                    @click="confirmForceDelete(resource)",
                                    v-tooltip="{title: messages.action_force_delete, boundary: 'window'}"
                                    v-if="wasSoftDeleted(resource) && canDelete")
                                    i.far.fa-trash-alt.text-danger
                                button-loading.btn.btn-sm.bg-transparent(
                                    v-if="wasSoftDeleted(resource)",
                                    @click="restore(resource)",
                                    v-tooltip="{title: messages.action_restore, boundary: 'window'}"
                                    :loading="isRestoring(resource)")
                                    i.fas.fa-redo.text-black-50(v-if="!isRestoring(resource)")
                    tr(v-if="resources.data.length === 0")
                        td(:colspan="columns.length + 2")
                            slot(name="empty-table") No data found

        pagination.border-top.pt-3.pb-2(:meta="resources.meta", :links="resources.links", @page="fetchResources" v-if="hasPage")

        modal(ref="confirmDelete", :centered="true", effect="zoomin")
            span(slot="title") {{ messages.confirmation }}
            .lead {{ messages.confirmation_to_delete }}
            div(slot="footer")
                button.btn.bg-transparent(type='button', data-dismiss='modal') {{ messages.confirmation_action_no }}
                button-loading.btn.btn-danger.ml-2(
                    type='button',
                    @click="remove(targetResource)",
                    :loading="isRemoving(targetResource)") {{ messages.confirmation_action_yes }}

        modal(ref="confirmBulkDelete", :centered="true", effect="zoomin")
            span(slot="title") {{ messages.confirmation }}
            .lead {{ messages.confirmation_to_bulk_delete }}
            div(slot="footer")
                button.btn.bg-transparent(type='button', data-dismiss='modal') {{ messages.confirmation_action_no }}
                button-loading.btn.btn-danger.ml-2(
                    type='button',
                    @click="removeBulk",
                    :loading="removing_bulk") {{ messages.confirmation_bulk_delete_yes }}

        confirmation(ref="confirmAction", :yes-label="messages.confirmation_action_yes", :no-label="messages.confirmation_action_no")
        confirmation(ref="confirmForceDelete", :yes-label="messages.confirmation_action_yes", :no-label="messages.confirmation_action_no")
</template>

<style lang="sass">
.resource-table
    .filter
        .dropdown-item
            border-radius: 0
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
                    color: red

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
        .btn-grey
            background-color: #e6eaec

        .resource-image
            max-width: 80px
            border-radius: 4px
</style>


<script>
import _ from 'lodash';
import axios from 'axios';
import qs from 'qs';

export default {
    props: {
        /**
         * Message when resources are deleted in a bulk.
         */
        bulkDeleteMessage: {
            default: 'Selected resources successfully deleted',
        },

        /**
         * The columns fields to be displayed.
         *
         * Example: columns: ['id', 'name', 'created_at']
         */
        columns: {
            required: true,
            type: Array,
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
         * Base Resource's URL
         */
        url: {
            default: null,
        },

        /**
         * Flag to display the bulk delete option.
         */
        hasBulkDelete: {
            default: true,
        },

        /**
         * Enable/disable bulk actions
         */
        hasBulkActions: {
            default: true,
        },

        /**
         * Flag to show the search bar or not
         */
        search: {
            default: null,
        },

        /**
         * Table options.
         */
        options: {
            required: false,
            default: () => ({
                headers: {},
                sortable: [],
                messages: {},
            }),
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
         * Permission to delete operations
         */
        canDelete: {
            default: true,
        },

        /**
         * Permission to edit operations
         */
        canEdit: {
            default: true,
        },

        /**
         * Permission to show operations
         */
        canShow: {
            default: true,
        },
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
            selected: [],
            sort: null,
            removing: [],
            removing_bulk: false,
            restoring: [],
            targetResource: null,
            appliedFilters: [],
            actionSelected: null,
            actionLoading: false,
            defaultMessages: {
                action_delete: 'Delete',
                action_force_delete: 'Delete Permanently',
                action_restore: 'Restore',
                action_select_all: 'Select All',
                action_unselect_all: 'Unselect All',
                action_clear_selection: 'Clear selection',
                action_delete_all: 'Delete All',
                label_selected: 'selected',
                action_apply_filters: 'Filters',
                confirmation: 'Confirmation',
                confirmation_to_delete: 'Are you sure you want to delete this record?',
                confirmation_to_bulk_delete: 'Are you sure you want to delete all selected resources?',
                confirmation_action_yes: "Yes, I'm Sure",
                confirmation_action_no: 'Cancel',
                confirmation_bulk_delete_yes: 'YES, DELETE ALL',
                warning: 'Attention',
                confirmation_to_force_delete: `Are you really sure you want to permanently destroy this resource? This action cannot be undone.`,
            },
        };
    },

    watch: {
        url() {
            this.refresh();
        },
        search: _.debounce(function() {
            this.refresh();
        }, 500),
    },

    computed: {
        baseUrl() {
            const { 0: base_url } = this.url.split('?');
            return base_url;
        },
        baseParams() {
            const { 1: params } = this.url.split('?');
            return params;
        },
        hasPage() {
            return this.resources.meta && this.resources.meta.last_page > 1;
        },
        ids() {
            return this.resources.data.map(r => r[this.trackBy]);
        },
        allSelected() {
            return !this.ids.some(id => !this.selected.includes(id)) && this.ids.length;
        },
        sortables() {
            return this.options.sortable;
        },
        bulkDestroyUrl() {
            return this.url + '/bulk-destroy';
        },
        hasActions() {
            return this.options.actions && this.options.actions.length;
        },
        filters() {
            return this.options.filters;
        },
        buttonFilterClasses() {
            return this.appliedFilters.length ? 'btn-sm btn-primary' : 'btn-sm btn-default';
        },
        _actions() {
            return this.hasActions ? this.options.actions : [];
        },
        messages() {
            return { ...this.defaultMessages, ...this.options.messages };
        },
    },

    created() {
        this.sort = this.defaultSort;
        if (this.url) {
            this.fetchResources();
        }
        if (!this.options) {
            this.options = {};
        }
        if (!this.options.sortable) {
            this.options.sortable = [];
        }
    },

    methods: {
        applyFilter(filter) {
            this.appliedFilters.push(filter);
        },

        arrayFromObject(object, keyName = 'key') {
            const arr = [];
            for (let key in object) {
                arr.push({ [keyName]: key, value: object[key] });
            }
            return arr;
        },

        toggleFilter(filter) {
            this.isAppliedFilter(filter) ? this.removeFilter(filter) : this.applyFilter(filter);
            this.refresh();
        },

        async performAction() {
            try {
                const confirmed = await this.checkConfirmationDialog();
                if (!confirmed) return;

                this.actionLoading = true;
                await this.actionSelected.callback(this.selected);
                this.$toasted.success('Action performed successfully');
            } catch (error) {
                typeof this.actionSelected.onError === 'function'
                    ? this.actionSelected.onError(error)
                    : this.$toasted.error('Oops! Something went wrong.');
            } finally {
                this.$refs.confirmAction.close();
                this.clearAction();
            }
        },

        async checkConfirmationDialog() {
            try {
                if (this.actionSelected.confirmation || this.actionSelected.confirm) {
                    await this.$refs.confirmAction.ask(this.actionSelected.confirmation || {});
                }
                return true;
            } catch (error) {
                return false;
            }
        },

        async confirmForceDelete(resource) {
            try {
                await this.$refs.confirmForceDelete.ask({
                    title: this.messages.warning,
                    message: this.messages.confirmation_to_force_delete,
                    dangerous: true,
                });
                await axios.delete(this.resourceUrl(resource, 'force'));
                this.$refs.confirmForceDelete.close();
                this.refresh();
            } catch (error) {
                this.$refs.confirmForceDelete.stopLoading();
            }
        },

        confirmRemove(resource) {
            this.targetResource = resource;
            this.$refs.confirmDelete.open();
        },

        confirmBulkDelete() {
            this.$refs.confirmBulkDelete.open();
        },

        async remove(resource) {
            this.removing.push(resource[this.trackBy]);
            await axios.delete(this.resourceUrl(resource));
            this.$toasted.show(this.deleteMessage);
            this.targetResource = null;
            this.removing.splice(this.removing.indexOf(resource[this.trackBy]), 1);
            this.refresh();
            this.$refs.confirmDelete.close();
        },

        async removeBulk() {
            this.removing_bulk = true;
            const ids = this.selected;
            await axios.post(this.bulkDestroyUrl, { ids });
            this.selected = [];
            this.removing_bulk = false;
            this.refresh();
            this.$refs.confirmBulkDelete.close();
            this.$toasted.show(this.bulkDeleteMessage);
        },

        removeFilter(filter) {
            this.appliedFilters.splice(this.appliedFilters.indexOf(filter), 1);
        },

        isAppliedFilter(filter) {
            return this.appliedFilters.includes(filter);
        },

        isAvatar(column) {
            if (!this.hasOption('avatars')) return false;

            return Array.isArray(this.options.avatars)
                ? this.options.avatars.includes(column)
                : Object.keys(this.options.avatars).includes(column);
        },

        isImage(column) {
            if (!this.hasOption('images')) return false;

            return Array.isArray(this.options.images)
                ? this.options.images.includes(column)
                : Object.keys(this.options.images).includes(column);
        },

        isRemoving(resource) {
            return resource && this.removing.includes(resource[this.trackBy]);
        },

        async fetchResources(page = 1) {
            this.loading = true;
            const search = this.search || null;
            const sort = this.sort;
            const filter = this.getPreparedFilters();
            const { data: resources } = await axios.get(this.url, {
                params: { page, search, sort, filter },
                paramsSerializer: params => {
                    return qs.stringify(params, { arrayFormat: 'brackets' });
                },
            });
            this.loading = false;
            this.resources = resources;
        },

        getPreparedFilters() {
            const filters = {};
            this.appliedFilters.forEach(filter => {
                if (filters[filter.field]) {
                    return (filters[filter.field] += ',' + filter.value);
                }
                filters[filter.field] = filter.value;
            });
            return filters;
        },

        refresh() {
            return this.fetchResources(this.page);
        },

        head(column) {
            let header = this.options.headers && this.options.headers[column];
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

        hasOption(optionName) {
            const option = this.options[optionName];

            if (!option) return false;

            return Array.isArray(option) ? option.length > 0 : Object.keys(option).length > 0;
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

        resourceUrl(resource, action = '') {
            const params = this.baseParams ? '?' + this.baseParams : '';
            const url = `${this.baseUrl}/${resource[this.trackBy]}`;
            return action ? `${url}/${action}${params}` : url + params;
        },

        editUrl(resource) {
            return this.resourceUrl(resource, 'edit');
        },

        async restore(resource) {
            const restoreUrl = this.resourceUrl(resource, 'restore');
            this.restoring.push(resource[this.trackBy]);
            await axios.post(restoreUrl);
            await this.refresh();
            this.restoring.splice(this.restoring.indexOf(resource[this.trackBy]), 1);
        },

        isResourceSelected(resource) {
            const id = resource[this.trackBy];
            return this.selected.includes(id);
        },

        isRestoring(resource) {
            return this.restoring.includes(resource[this.trackBy]);
        },

        isSortable(column) {
            return this.sortables.includes(column);
        },

        selectAll() {
            this.ids.filter(id => !this.selected.includes(id)).forEach(id => this.selected.push(id));
        },

        unselectAll() {
            this.ids.filter(id => this.selected.includes(id)).forEach(id => this.selected.splice(this.selected.indexOf(id), 1));
        },

        clearSelection() {
            this.selected = [];
        },

        clearAction() {
            this.actionSelected = null;
            this.actionLoading = false;
        },

        onCheck(resource) {
            const id = resource[this.trackBy];
            this.selected.includes(id) ? this.selected.splice(this.selected.indexOf(id), 1) : this.selected.push(id);
        },

        // onSearch: _.debounce(function() {
        //     this.refresh();
        // }, 500),

        checkboxId(resource) {
            return 'checkbox-' + resource[this.trackBy];
        },

        wasSoftDeleted(resource) {
            return resource.deleted_at;
        },

        objGet(obj, str) {
            return str.split('.').reduce((a, c) => (a ? a[c] : null), obj);
        },
    },
};
</script>
