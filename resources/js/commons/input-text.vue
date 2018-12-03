<template lang="pug">
    .form-group
        label(v-if="label") {{ label }}

        input.form-control(:type="type",
            :min="min",
            :value="value",
            @input="$emit('input', $event.target.value)",
            :placeholder="placeholder",
            :class="{\
                [inputClass]: inputClass,\
                'is-invalid': form && form.errors.has(field),\
                'left-icon': leftIcon,\
                'right-icon': rightIcon,\
            }",
            :disabled="disabled",
            :readonly="readonly")

        i.left-icon(v-if="leftIcon", :class="leftIcon")
        i.right-icon(v-if="rightIcon", :class="rightIcon")


        p.invalid-feedback(v-if="form && form.errors.has(field)") {{ form.errors.get(field) }}
</template>

<style lang="sass" scoped>
.form-group
    position: relative
    input.left-icon
        padding-left: 36px
    i.left-icon
        position: absolute
        top: 10px
        left: 10px
        opacity: 0.5
    input.right-icon
        padding-right: 36px
    i.right-icon
        position: absolute
        top: 10px
        right: 10px
        opacity: 0.5
</style>

<script>
export default {
    props: {
        label: { default: null },
        type: { default: 'text' },
        min: { default: null },
        placeholder: { default: null },
        form: { default: null },
        field: { default: null },
        value: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        inputClass: { default: '' },
        leftIcon: { default: null },
        rightIcon: { default: null }
    }
};
</script>
