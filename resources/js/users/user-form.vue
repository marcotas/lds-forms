<template lang="pug">
    div
        h3 User Form

        .row.mt-4
            .col-md-2
                div.text-center.mb-2 Avatar
                input-avatar.m-auto(@change="form.new_avatar = $event", :image="form.avatar && form.avatar.thumb")
            .col-md-8
                input-text(label="Name", v-model="form.name", :form="form", field="name")
                input-text(label="Email", v-model="form.email", :form="form", field="email")
                input-select(label="Gender", v-model="form.gender", :form="form", field="gender", :options="genders")
                input-text(label="Password", type="password", v-model="form.password", :form="form", field="password")
                input-text(label="Password Confirmation", type="password", v-model="form.password_confirmation",
                    :form="form", field="password_confirmation")

                .form-group
                    label.form-label Active
                    div
                        toggle-button(v-model="form.active")

                .my-5
                    a.btn.btn-default.mr-2(:href="$route('admin.users.index')")
                        i.fa.fa-arrow-left.mr-2
                        | Back to list
                    button-loading.btn.btn-primary(:loading="form.submitting" @click="submit") Submit
                    save-indicator.ml-3(:saving="form.submitting")
</template>

<script>
export default {
    props: {
        user: { default: null, type: Object }
    },

    data() {
        return {
            form: new Form(this.user || {}, 'multipart'),
            genders: [{ id: 'male', name: 'Male' }, { id: 'female', name: 'Female' }]
        };
    },

    methods: {
        async submit() {
            this.form.id ? await this.update() : await this.create();
        },

        async update() {
            const { data: user } = await this.form.post(this.$route('api.users.update', { user: this.form.id }));
            this.form = new Form(user, 'multipart');
        },

        async create() {
            const { data: user } = await this.form.post(this.$route('api.users.store'));
            window.location.href = this.$route('admin.users.edit', { user: user.id });
        }
    }
};
</script>
