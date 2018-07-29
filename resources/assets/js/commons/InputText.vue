<template>
    <div class="form-group">
        <label v-if="label">{{ label }}</label>

        <div class="input-group" v-if="group">
            <div class="input-group-prepend" v-if="groupPrepend">
                <span class="input-group-text">{{ groupPrepend }}</span>
            </div>

            <input :type="type" class="form-control"
                   :value="value"
                   @input="$emit('input', $event.target.value)"
                   :placeholder="placeholder"
                   :class="{'is-invalid': form && form.errors.has(field)}"
                   :disabled="disabled"
                   :readonly="readonly">

            <div class="input-group-append" v-if="groupAppend">
                <span class="input-group-text">{{ groupAppend }}</span>
            </div>

        </div>

        <input v-else
               :type="type" class="form-control"
               :min="min"
               :value="value"
               @input="$emit('input', $event.target.value)"
               :placeholder="placeholder"
               :class="{'is-invalid': form && form.errors.has(field)}"
               :disabled="disabled"
               :readonly="readonly">


        <p class="invalid-feedback" v-if="form && form.errors.has(field)">{{ form.errors.get(field) }}</p>
    </div>
</template>

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
    disabled: { default: false },
    readonly: { default: false },
    group: { default: false },
    groupPrepend: { default: null },
    groupAppend: { default: null }
  }
};
</script>
