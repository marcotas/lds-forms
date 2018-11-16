<template lang="pug">
    div
        h3 Topic Form

        input-text(ref="firstInput", label="Name", v-model="form.name", :form="form", field="name")
        input-text(label="Link", v-model="form.link", :form="form", field="link")
        input-text(label="Date", type="date", v-model="form.date", :form="form", field="date")

        input-select-2(label="Speaker", placeholder="Click to choose", :url="$route('api.users.index')",
            searchable-model="users",
            image-as="avatar.thumb",
            :force-image="true",
            v-model="form.user_id",
            :options="userOptions"
            :form="form",
            field="user_id")

        a.btn.btn-default.mr-2(:href="$route('admin.topics.index')")
            i.fa.fa-arrow-left.mr-2
            | Back to list
        button.btn.btn-primary.mr-2(@click="submitAnother" v-if="!form.id") Create & Continue
        button-loading.btn.btn-primary(:loading="form.submitting" @click="save") Save
        save-indicator.ml-3(:saving="form.submitting")
</template>

<script>
export default {
    props: {
        topic: { default: null, type: Object }
    },

    data() {
        return {
            form: new Form(this.topic || {}),
            userOptions: []
        };
    },

    created() {
        if (this.form.user_id) this.fetchUser(this.form.user_id);
    },

    methods: {
        async save() {
            const topic = this.form.id ? await this.update() : await this.create();
            window.location.href = this.$route('admin.topics.edit', { topic: topic.id });
        },

        async submitAnother() {
            const topic = this.form.id ? await this.update() : await this.create();
            this.form = new Form();
            this.$refs.firstInput.focus();
        },

        async update() {
            const { data: topic } = await this.form.put(this.$route('api.topics.update', { topic: this.form.id }));
            this.form = new Form(topic.data);
            return topic.data;
        },

        async create() {
            const { data: topic } = await this.form.post(this.$route('api.topics.store'));
            return topic.data;
        },

        async fetchUser(id) {
            const { data: user } = await this.$axios.get(this.$route('api.users.show', { user: id }));
            this.userOptions = [user.data];
        }
    }
};
</script>
