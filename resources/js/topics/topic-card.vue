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
            div.d-flex.align-items-center.time
                small.text-black-50 {{ time }}
                small.ml-2.text-primary.text-uppercase(v-if="topic.invited_at",
                    :class="topic.confirmed_at ? 'text-success' : 'text-info'")
                    i.fa.mr-1(:class="topic.confirmed_at ? 'fa-check-double' : 'fa-check'")
                    | {{ topic.confirmed_at ? 'Confirmed' : 'Invited' }}
</template>

<style lang="sass" scoped>
.time
    position: relative
    bottom: -4px
</style>


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
        },
        time() {
            return this.topic.position * 5 + ' minutes';
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
