<template lang="pug">
    .card.border-0.shadow-sm.mb-3.cursor-pointer.transitioned(
        @click="$emit('click', $event)"
        class="hover:shadow")
        .card-body
            .float-right.ml-2
                img.avatar.rounded-circle(ref="avatar", :src="avatar", v-if="topic.speaker"
                    :title="title"
                    data-toggle="tooltip"
                    data-placement="top")
            .lead {{ topic.name }}
</template>

<script>
export default {
    props: ['topic'],

    data() {
        return {
            tooltip: null
        };
    },

    mounted() {
        this.updateTooltip();
    },

    watch: {
        title() {
            this.$nextTick(() => this.updateTooltip(), 100);
        }
    },

    computed: {
        avatar() {
            return this.$obj_get(this.topic, 'speaker.avatar.thumb') || '/img/default-avatar.png';
        },
        title() {
            const name = this.$obj_get(this.topic, 'speaker.name');
            return `Assigned to ${name}`;
        }
    },

    methods: {
        updateTooltip() {
            if (this.tooltip) {
                this.tooltip.tooltip('dispose');
            }

            if (this.$refs.avatar) {
                this.tooltip = $(this.$refs.avatar).tooltip({
                    boundary: 'window',
                    delay: { show: 500, hide: 0 }
                });
            }
        }
    }
};
</script>
