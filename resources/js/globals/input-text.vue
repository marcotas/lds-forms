<template>
    <div class="form-group position-relative">
        <label v-if="label" :class="{ 'text-danger': (form && form.errors.has(field)) || hasError }">{{ label }}</label>

        <div class="input-group position-relative" v-if="group">
            <div class="input-group-prepend" v-if="groupPrepend">
                <span class="input-group-text">{{ groupPrepend }}</span>
            </div>

            <input :type="type" ref="input"
                :value="value"
                :min="min"
                :minlength="minlength"
                :max="max"
                :maxlength="maxlength"
                :step="step"
                @focus="$emit('focus', $event)"
                @blur="$emit('blur', $event)"
                @input="$emit('input', $event.target.value)"
                :placeholder="placeholder"
                :class="{ [inputClass]: inputClass, 'is-invalid': (form && form.errors.has(field)) || hasError }"
                :disabled="disabled"
                :readonly="readonly">

            <div class="input-group-append" v-if="groupAppend">
                <span class="input-group-text">{{ groupAppend }}</span>
            </div>

        </div>

        <div v-else class="position-relative">
            <input ref="input"
                :type="type" class="form-control"
                :min="min"
                :minlength="minlength"
                :max="max"
                :maxlength="maxlength"
                :value="value"
                :step="step"
                @focus="$emit('focus', $event)"
                @blur="$emit('blur', $event)"
                @input="$emit('input', $event.target.value)"
                :placeholder="placeholder"
                :class="{
                    [inputClass]: inputClass,
                    'is-invalid': (form && form.errors.has(field)) || hasError,
                    'left-icon': leftIcon,
                    'right-icon': rightIcon,
                }"
                :disabled="disabled"
                :readonly="readonly">
            <i v-if="leftIcon" class="left-icon" :class="leftIcon"></i>
            <i v-if="rightIcon && !(form && form.errors.has(field || hasError))" class="right-icon" :class="rightIcon"></i>

            <p class="invalid-feedback mb-0" v-if="form && form.errors.has(field)">{{ form.errors.get(field) }}</p>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.form-group
    input.left-icon
        padding-left: 2rem
    i.left-icon
        position: absolute
        top: 11px
        left: 10px
        opacity: 0.5
    input.right-icon
        padding-right: 2rem
    i.right-icon
        position: absolute
        top: 11px
        right: 10px
        opacity: 0.5

    input.form-control-lg
        & ~ i.left-icon,
        & ~ i.right-icon
            top: 14px
            font-size: 1.2rem
    input.form-control-sm
        & ~ i.left-icon,
        & ~ i.right-icon
            top: 14px
</style>


<script>
export default {
    props: {
        label: { default: null },
        type: { default: 'text' },
        min: { default: null },
        minlength: { default: null },
        max: { default: null },
        maxlength: { default: null },
        step: { default: null },
        placeholder: { default: null },
        form: { default: null },
        field: { default: null },
        value: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        group: { default: false },
        groupPrepend: { default: null },
        groupAppend: { default: null },
        inputClass: { default: 'form-control' },
        leftIcon: { default: null },
        rightIcon: { default: null },
        autofocus: { default: false },
        hasError: { default: false },
    },

    mounted() {
        if (this.autofocus) {
            this.focus();
        }
    },

    methods: {
        focus() {
            this.$nextTick(() => {
                if (this.$refs.input) {
                    this.$refs.input.focus();
                }
            });
        },
    },
};
</script>
