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
                'has-icon': icon,\
                'icon-left': icon && iconPosition === 'left',\
                'icon-right': icon && iconPosition === 'right'\
            }",
            :disabled="disabled",
            :readonly="readonly")

        i.text-black-50.fa-fw(v-if="icon", :class="icon")


        p.invalid-feedback(v-if="form && form.errors.has(field)") {{ form.errors.get(field) }}
</template>

<style lang="sass" scoped>
.form-group
    position: relative

    .has-icon
        &.icon-left
            padding-left: 2.4rem
            & ~ i
                left: 10px
        &.icon-right
            padding-right: 2.4rem
            & ~ i
                right: 10px
        & ~ i
            position: absolute
            top: 10px
            font-size: 1rem
</style>

<script>
export default {
  props: {
    label: { default: null },
    type: { default: "text" },
    min: { default: null },
    placeholder: { default: null },
    form: { default: null },
    field: { default: null },
    value: { default: null },
    icon: { default: null },
    iconPosition: { default: 'left' },
    disabled: { default: false },
    readonly: { default: false },
    inputClass: { default: '' },
  }
};
</script>
