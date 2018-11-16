<template lang="pug">
    div
        label.form-label(v-if="label") {{ label }}
        div(v-click-outside="outsideClick")
            .form-control.text-black-50.cursor-pointer(@click="toggleOpen")
                .d-flex.flex-wrap.align-items-center(v-if="!objSelected")
                    | {{ placeholder }}
                    i.ml-auto.fa.fa-chevron-down
                div.d-flex.align-items-center.flex-wrap(slot="selected", v-if="objSelected")
                    div(v-if="sourceImage || forceImage")
                        img.small.avatar.rounded-circle(:src="sourceImage || defaultImage")
                    .ml-2 {{ objSelected[labelBy] }}
                    i.ml-auto.fa.fa-times(@click.stop="removeSelection")
            .form-group.position-relative
                div.dropdown-menu.overflow-hidden.p-0.w-100(:class="{'d-block': opened}")
                    input.search-input.form-control.border-0.dropdown-item-text(
                        ref="input",
                        v-model="search",
                        @input="onSearch",
                        placeholder="Type to search...")
                    .scrollable
                        em.dropdown-item-text.text-muted(v-if="noOptions") There is no options. Sorry.
                        a.dropdown-item.cursor-pointer.d-flex.align-items-center.flex-wrap(
                            href="#",
                            :class="{'active': isSelected(option)}"
                            v-for="option of innerOptions",
                            :key="option[trackBy]"
                            @click.stop.prevent="select(option)")
                            .mr-2(v-if="optionSourceImage(option)")
                                img.avatar.rounded-circle(:src="optionSourceImage(option)")
                            | {{ option[labelBy] }}
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
.avatar
    &.small
        max-height: 23px
</style>


<script>
import _ from 'lodash';

export default {
    props: {
        value: { default: null },
        options: { default: () => [] },
        label: String,
        placeholder: String,
        url: String,
        trackBy: { default: 'id' },
        labelBy: { default: 'name' },
        forceImage: { default: false },
        defaultImage: { default: 'https://www.gravatar.com/avatar?d=mp&s=500' },
        searchable: Boolean,
        searchableModel: String,
        searchableFields: {
            default: () => ['id', 'name']
        },
        imageAs: { default: 'image' }
    },

    data() {
        return {
            opened: false,
            search: null,
            selected: this.value,
            innerOptions: [],
            noOptions: false
        };
    },

    watch: {
        value() {
            this.selected = this.value;
        },
        options() {
            this.innerOptions = this.options;
            this.noOptions = this.innerOptions.length === 0 && !this.objSelected;
            console.log('no options ? ', this.noOptions, this.innerOptions, this.objSelected);
        }
    },

    computed: {
        sourceImage() {
            return this.$obj_get(this.objSelected, this.imageAs);
        },
        objSelected() {
            return this.innerOptions.find(obj => obj[this.trackBy] === this.selected);
        }
    },

    methods: {
        toggleOpen() {
            this.opened = !this.opened;
            this.noOptions = false;
            if (this.opened) {
                this.search = null;
                this.$nextTick(() => this.$refs.input.focus());
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
                sort: this.labelBy
            };
            if (this.searchableModel) {
                params.fields = { [this.searchableModel]: this.searchableFields.join(',') };
            }
            const { data: innerOptions } = await this.$axios.get(this.url, { params });
            this.innerOptions = innerOptions.data ? innerOptions.data : innerOptions;
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
            return this.$obj_get(option, this.imageAs) || (this.forceImage && this.defaultImage);
        },

        onSearch: _.debounce(async function() {
            await this.fetchSource();
        }, 500)
    }
};
</script>
