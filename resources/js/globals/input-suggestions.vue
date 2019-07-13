<template>
    <div class="position-relative input-suggestions" @keyup.down.stop="next" @keyup.up.stop="prev"
        @keyup.enter.stop="selectCurrent"
        v-click-outside="close">
        <input-text v-model="search" @input="onSearch" class="mb-1 input-suggestions__input" :left-icon="leftIcon" :placeholder="placeholder"
            :form="form" :field="field"
            :right-icon="rightIcon"
            @focus="onFocus">
        </input-text>

        <ul class="list-group suggestions shadow rounded" :class="{ visible }">
            <li ref="suggestion" v-for="(item, index) of options" :key="index"
                @click="select(item)"
                :class="{active: isSelected(item), highlighted: isCurrent(item)}"
                style="min-height: fit-content"
                class="list-group-item px-3 py-2 list-group-item-action t-cursor-pointer overflow-hidden">
                <slot name="option" :item="item" :selected="isSelected(item)" :highlighted="isCurrent(item)">
                    {{ item[labelBy] }}
                </slot>
            </li>

            <li v-if="search && !options.length && allowCreate" class="list-group-item px-3 py-2 list-group-item-action t-cursor-pointer"
                @click="onCreate">
                <slot name="footer" :search="search">Add <b>{{ search }}</b></slot>
            </li>
        </ul>
    </div>
</template>

<style lang="sass" scoped>
.list-group.suggestions
    position: absolute
    width: 100%
    max-height: 300px
    overflow: auto
    visibility: hidden
    background-color: white
    transition: .3s ease
    opacity: 0
    perspective: 1000px
    will-change: opacity
    z-index: 99
    &.visible
        opacity: 1
        visibility: visible
    .highlighted:not(.active)
        background-color: #eee
</style>


<script>
export default {
    props: {
        value: { default: null },
        objectValue: { default: false, type: Boolean },
        leftIcon: { default: null },
        placeholder: { default: null },
        trackBy: { default: 'id' },
        labelBy: { default: 'name' },
        openOnFocus: { default: true },
        closeOnSelect: { default: true },
        loading: { default: false },
        options: { default: () => [] },
        form: { default: null },
        field: { default: null },
        allowCreate: { default: false },
    },

    model: {
        event: 'selected',
    },

    data() {
        return {
            suggestions: [],
            selected: null,
            current: null,
            visible: false,
            search: null,
        };
    },

    mounted() {
        this.updateDisplay();
    },

    computed: {
        changed() {
            return Boolean((this.search && !this.selected) || (this.selected && this.selected[this.labelBy] !== this.search));
        },

        rightIcon() {
            if (this.loading) return 'fa fa-spinner fa-spin';
            return this.visible ? 'fa fa-angle-up' : 'fa fa-angle-down';
        },
    },

    watch: {
        value() {
            this.updateDisplay();
            console.log('value changed');
        },

        options() {
            this.updateDisplay();
        },

        current() {
            const index = this.options.indexOf(this.current);

            if (index >= 0) {
                this.$refs.suggestion[index].scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                });
            }
        },
    },

    methods: {
        select(item) {
            this.selected = item;
            this.search = this.selected[this.labelBy];
            const value = this.objectValue ? this.selected : this.selected[this.trackBy];
            this.$emit('selected', value);
            this.current = null;
            if (this.closeOnSelect) this.close();
        },

        selectCurrent() {
            if (this.visible) {
                if (!this.current) return;
                return this.select(this.current);
            }

            if (this.options.length) {
                this.visible = true;
            }
        },

        isSelected(item) {
            return this.selected && this.selected[this.trackBy] === item[this.trackBy];
        },

        isCurrent(item) {
            return this.current && this.current[this.trackBy] === item[this.trackBy];
        },

        close() {
            this.visible = false;
            this.search = this.selected && this.selected[this.labelBy];
        },

        clear() {
            this.search = null;
            this.selected = null;
            this.current = null;
        },

        next() {
            if (!this.options.length) return;
            this.visible = true;

            const index = this.options.indexOf(this.current);

            if (!this.current || this.options.length <= index + 1) {
                this.current = this.options[0];
                return;
            }

            this.current = this.options[index + 1];
        },

        prev() {
            if (!this.options.length) return;
            this.visible = true;

            const index = this.options.indexOf(this.current);

            if (!this.current || index === 0) {
                this.current = this.options[this.options.length - 1];
                return;
            }

            this.current = this.options[index - 1];
        },

        onFocus() {
            this.visible = this.openOnFocus || this.visible;
        },

        onSearch() {
            if (this.changed) {
                this.$emit('search', this.search);
            }
        },

        onCreate() {
            this.$emit('create', this.search);
        },

        updateDisplay() {
            if (!this.value) return (this.selected = null);

            const id = this.objectValue ? this.value[this.trackBy] : this.value;
            this.selected = this.options.find(op => op[this.trackBy] === id);
        },
    },
};
</script>
