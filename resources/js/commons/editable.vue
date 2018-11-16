<template lang="pug">
    component(:is="tag", :contenteditable="true", @input="onChange", v-html="value", :class="{ done }")
</template>

<script>
export default {
    props: {
        tag: { default: 'span' },
        value: { default: null, type: String },
    },
    data() {
        return {
            done: false,
        };
    },
    methods: {
        onChange(event) {
            this.$emit('input', event.target.innerText);

            let sel = window.getSelection();
            let currentOffset = sel.focusOffset;
            this.$nextTick(() => {
                this.moveCaret(currentOffset);
            });
        },

        getTextNode() {
            return this.$el.childNodes.length ? this.$el.childNodes[0] : null;
        },

        moveCaret(position) {
            if (!window.getSelection) {
                throw new Error('This browser is not supported.');
            }

            const textNode = this.getTextNode();

            if (!textNode) {
                return;
            }

            var range = document.createRange();
            var sel = window.getSelection();

            range.setStart(textNode, position);
            range.collapse(true);
            sel.empty();
            sel.addRange(range);
        },
    },
}
</script>
