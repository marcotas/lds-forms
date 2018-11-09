<template lang="pug">
    modal(ref="modal")
        span(slot="title") Topic
        input-text(label="Name", v-model="form.name", :form="form", field="name")
        input-text(label="Link", v-model="form.link", :form="form", field="link")
        input-text(label="Date", type="date", v-model="form.date", :form="form", field="date")

        input-select-2(label="Speaker", placeholder="Click to choose", :url="$route('api.users.index')",
            searchable-model="users",
            image-as="avatar.thumb",
            v-model="form.user_id",
            :options="userOptions"
            :form="form",
            field="user_id")
        //- pre form: {{ form }}

        div(slot="footer")
            button.btn.bg-transparent.mr-2(type="button", @click="close") Cancel
            button-loading.btn.btn-primary(:loading="form.submitting" @click="save") Save
</template>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    data() {
        return {
            form: new Form(),
            userOptions: []
        };
    },

    methods: {
        setup(topic = {}) {
            this.form = new Form(topic || {});
            this.form.user_id && this.fetchUser(this.form.user_id);
            return this;
        },
        async save() {
            const { data: topic } = await this.form.put(this.$route('api.topics.update', { topic: this.form.id }));
            this.$emit('updated', topic.data);
            this.close();
        },

        async fetchUser(id) {
            const { data: user } = await this.$axios.get(this.$route('api.users.show', { user: id }));
            this.userOptions = [user.data];
        }
    }
};
</script>
