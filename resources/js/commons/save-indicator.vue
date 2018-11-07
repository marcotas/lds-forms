<template lang="pug">
    span.save-indicator(:class="{ 'text-success': !saving, 'text-danger': error, 'hide-slow': !saving }")
        i.fa(:class="{ 'fa-spinner fa-spin': saving, 'fa-check': !saving && !error, 'fa-exclamation-circle': error }")
        |  {{ text }}

</template>

<style lang="sass" scoped>
.save-indicator
    &.hide-slow
        opacity: 0
        transition: opacity 1s ease 3s

</style>


<script>
import _ from 'lodash';

export default {
    props: {
        saving: { default: false },
        error: { default: false }
    },

    watch: {
        saving: _.debounce(function() {}, 1000)
    },

    computed: {
        text() {
            return this.saving ? 'Saving...' : 'Saved';
        }
    }
};
</script>
