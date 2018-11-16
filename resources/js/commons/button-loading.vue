<template lang="pug">
    button(:disabled="loading || disabled", @click.prevent.stop="$emit('click', $event)")
        span(v-if="loading")
          i.fa.fa-spin.fa-spinner.mr-2
        slot
</template>

<script>
export default {
  props: {
    loading: { default: false, type: Boolean },
    disabled: { default: false },
  }
};
</script>
