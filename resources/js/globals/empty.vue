<template>
    <div class="mx-auto" style="height: 100%">
        <div class="empty-data">
            <component :is="type" :color="color"></component>
        </div>

        <slot>
            <h3 class="text-center my-4">{{ title }}</h3>
        </slot>
    </div>
</template>
<script>
import * as components from './svg';

export default {
    components: { ...components },

    props: {
        title: { default: 'No data' },
        color: { default: '#488dc9' },
        type: { default: 'default' },
    },
};
</script>
