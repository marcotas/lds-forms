<template lang="pug">
    .mb-3(@click="$emit('click', $event)")
        label.form-label(v-if="label") {{ label }}
        div(v-click-outside="outsideClick")
            .form-control.cursor-pointer(@click="toggleOpen")
                .d-flex.flex-wrap.align-items-center.text-black-50(v-if="!objSelected")
                    span.mr-2 {{ placeholder }}
                    i.ml-auto.fa.fa-chevron-down
                div.d-flex.align-items-center(slot="selected", v-if="objSelected")
                    .mr-2(v-if="sourceImage || forceImage")
                        avatar(:username="objSelected[labelBy]", color="white", :size="23", :src="sourceImage || defaultImage")
                    .mr-2(:style="{ maxWidth }") {{ objSelected[labelBy] }}
                    i.ml-auto.fa.fa-times(@click.stop="removeSelection")
            .form-group.position-relative.mb-0
                div.dropdown-menu.overflow-hidden.p-0.w-100(:class="{'d-block': opened}")
                    input.search-input.form-control.border-0.dropdown-item-text(
                        v-if="searchable"
                        ref="input",
                        v-model="search",
                        @input="onSearch",
                        :placeholder="searchPlaceholder")
                    .scrollable
                        em.dropdown-item-text.text-muted(v-if="shouldDisplayNoOptionsMessage") {{ searchEmptyMessage }}
                        a.dropdown-item.cursor-pointer.d-flex.align-items-center.flex-wrap(
                            href="#",
                            :class="{'active': isSelected(option)}"
                            v-for="(option, index) of filteredOptions",
                            :key="index"
                            @click.stop.prevent="select(option)")
                            .mr-2(v-if="optionSourceImage(option) || forceImage")
                                avatar(:size="23", color="white", :username="getLabelOf(option)", :src="optionSourceImage(option)")
                            | {{ getLabelOf(option) }}
</template>

<style lang="sass" scoped>
.form-control
    height: initial
input.search-input
    &:focus
        box-shadow: none
.scrollable
    overflow-y: auto
    max-height: 300px
.dropdown-item, .dropdown-item-text
    padding: 0.25rem 1rem
.overflow-hidden
    overflow: hidden
</style>


<script>
import _ from 'lodash';
import qs from 'qs';

export default {
    props: {
        value: { default: null },
        options: { default: () => [] },
        label: String,
        placeholder: { default: 'Choose an option' },
        url: String,
        async: { default: true },
        trackBy: { default: 'id' },
        labelBy: { default: 'name' },
        forceImage: { default: false },
        defaultImage: { default: '' },
        searchable: { default: true },
        searchableModel: String,
        searchEmptyMessage: { default: 'There is no options. Sorry.' },
        searchPlaceholder: { default: 'Type to search...' },
        searchableFields: {
            default: () => ['id', 'name'],
        },
        imageAs: { default: 'image' },
        maxWidth: { default: null },
    },

    data() {
        return {
            opened: false,
            search: null,
            selected: this.value,
            innerOptions: this.options,
            noOptions: Boolean(!this.searchable && !this.options.length),
        };
    },

    mounted() {
        console.log('mounted select 2', this.noOptions);
    },

    watch: {
        value() {
            this.selected = this.value;
        },

        options() {
            this.innerOptions = this.options;
            this.noOptions = this.innerOptions.length === 0 && !this.objSelected;
        },
    },

    computed: {
        sourceImage() {
            return this.$obj_get(this.objSelected, this.imageAs);
        },

        objSelected() {
            return this.innerOptions.find(obj => obj[this.trackBy] === this.selected);
        },

        hasNoOptions() {
            return !this.innerOptions.length;
        },

        shouldDisplayNoOptionsMessage() {
            return this.hasNoOptions;
        },

        filteredOptions() {
            if (this.async || !this.searchable || !this.search) return this.innerOptions;

            return this.innerOptions.filter(option => option[this.labelBy].toLowerCase().includes(this.search.toLowerCase()));
        },
    },

    methods: {
        toggleOpen() {
            this.opened = !this.opened;
            this.noOptions = false || !this.searchable;
            if (this.opened) {
                this.search = null;
                this.$nextTick(() => this.$refs.input && this.$refs.input.focus());
                return this.$emit('opened');
            }
            return this.$emit('closed');
        },

        outsideClick() {
            this.opened = false;
        },

        async fetchSource() {
            const params = {
                per_page: 100,
                search: this.search,
                sort: this.labelBy,
            };

            if (this.searchableModel) {
                params.fields = { [this.searchableModel]: this.searchableFields.join(',') };
            }

            const paramsSerializer = params => {
                return qs.stringify(params, { arrayFormat: 'brackets' });
            };

            const { data: innerOptions } = await this.$axios.get(this.url, { params, paramsSerializer });

            this.innerOptions = innerOptions.data ? innerOptions.data : innerOptions || [];
            this.noOptions = this.innerOptions.length === 0 && !this.objSelected;
        },

        select(option) {
            this.selected = option[this.trackBy];
            this.opened = false;
            this.$emit('input', this.selected);
        },

        removeSelection() {
            this.selected = null;
            this.$emit('input', this.selected);
        },

        isSelected(option) {
            return this.objSelected && this.objSelected[this.trackBy] === option[this.trackBy];
        },

        optionSourceImage(option) {
            return this.$obj_get(option, this.imageAs);
        },

        getLabelOf(option) {
            if (typeof option === 'string') return option;
            return option[this.labelBy];
        },

        onSearch: _.debounce(async function() {
            if (!this.async || !this.url) return;
            await this.fetchSource();
        }, 500),
    },
};
</script>
