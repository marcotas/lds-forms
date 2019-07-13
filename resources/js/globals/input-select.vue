<template>
    <div class="form-group">
        <label v-if="label" :class="{ 'text-danger': form.errors.has(field) }">{{ label }}</label>

        <select class="form-control"
                v-model="selected"
                @change="onSelect"
                :class="{ [inputClass]: inputClass, 'is-invalid': form.errors.has(field) }"
                :disabled="disabled"
                :readonly="readonly">

            <option :value="null" selected disabled v-if="placeholder">{{ placeholder }}</option>
            <option v-for="option of options"
                    :value="getValueFor(option)"
                    :key="getKeyFor(option)">
                {{ getLabelFor(option) }}
            </option>
        </select>

        <p class="invalid-feedback" v-if="form.errors.has(field)">{{ form.errors.get(field) }}</p>
    </div>
</template>

<script>
export default {
    props: {
        label: { default: null },
        labeledBy: { default: 'name' },
        trackBy: { default: 'id' },
        objectValue: { default: false },
        value: { default: null },
        options: { default: () => [] },
        placeholder: { default: null },
        form: { default: () => new Form() },
        field: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        inputClass: { default: '' },
    },

    data() {
        return {
            selected: this.value,
        };
    },

    watch: {
        value() {
            this.selected = this.value;
        },
    },

    methods: {
        onSelect() {
            this.$emit('input', this.selected);
        },
        getValueFor(option) {
            if (this.objectValue) return option;
            if (!_.isObject(option)) return option;
            return option[this.trackBy];
        },
        getKeyFor(option) {
            if (!_.isObject(option)) return option;
            return option[this.trackBy];
        },
        getLabelFor(option) {
            if (!_.isObject(option)) return option;
            return option[this.labeledBy];
        },
    },
};
</script>
