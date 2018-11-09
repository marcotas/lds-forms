<template lang="pug">
    .avatar-uploader.rounded-circle.text-white.cursor-pointer.d-flex.flex-column.align-items-center.justify-content-center(
        @click='selectFile', :style='{ width, height: width, background }')
        i.fa.fa-camera.fa-3x(v-if='!source')
        slot
        input(ref='input', accept='image/*', type='file', hidden='', @change='preview')
</template>

<style lang="scss" scoped>
.avatar-uploader {
    overflow: hidden;
}
</style>


<script>
export default {
    props: {
        width: { default: '100px' },
        image: { default: null }
    },
    data() {
        return {
            reader: null,
            source: this.image,
            file: null
        };
    },
    computed: {
        background() {
            if (!this.source) return '#b0b0b8';
            return `url(${this.source}) center center / cover no-repeat`;
        }
    },
    methods: {
        selectFile() {
            this.$refs.input.click();
        },
        preview(event) {
            this.file = event.target.files[0];
            this.reader = new FileReader();
            this.reader.onload = this.onFileLoad.bind(this);
            this.reader.readAsDataURL(this.file);
        },
        onFileLoad() {
            this.source = this.reader.result;
            this.$emit('change', this.file);
        }
    }
};
</script>
