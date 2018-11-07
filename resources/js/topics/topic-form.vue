<template lang="pug">
    div
        h3 Topic Form

        input-text(label="Name", v-model="form.name", :form="form", field="name")
        input-text(label="Link", v-model="form.link", :form="form", field="link")
        input-text(label="Date", type="date", v-model="form.date", :form="form", field="date")

        a.btn.btn-default.mr-2(:href="$route('admin.topics.index')")
            i.fa.fa-arrow-left.mr-2
            | Back to list
        button-loading.btn.btn-primary(:loading="form.submitting" @click="submit") Submit
        save-indicator.ml-3(:saving="form.submitting")
</template>

<script>
export default {
    props: {
        topic: { default: null, type: Object }
    },

    data() {
        return {
            form: new Form(this.topic || {})
        };
    },

    methods: {
        async submit() {
            this.form.id ? await this.update() : await this.create();
        },

        async update() {
            const { data: topic } = await this.form.put(this.$route('api.topics.update', { topic: this.form.id }));
            this.form = new Form(topic);
        },

        async create() {
            const { data: topic } = await this.form.post(this.$route('api.topics.store'));
            window.location.href = this.$route('admin.topics.edit', { topic: topic.id });
        }
    }
};
</script>
