<template lang="pug">
    modal(ref="modal")
        span(slot="title") Topic
        input-text(label="Name", v-model="form.name", :form="form", field="name")
        input-text(label="Link", v-model="form.link", :form="form", field="link")
        input-text(label="Date", type="date", v-model="form.date", :form="form", field="date")
        input-select(label="Position", v-model="form.position", :form="form", field="position",
            :options="positionOptions")

        input-select-2(label="Speaker", placeholder="Click to choose", :url="$route('api.users.suggestions')",
            searchable-model="users",
            image-as="avatar.thumb",
            :force-image="true",
            v-model="form.user_id",
            :options="userOptions"
            :form="form",
            @opened="suggestUsers"
            field="user_id")

        .d-flex
            .mr-3
                toggle-button.mb-0(
                    :value="!!form.invited_at",
                    :sync="true",
                    @change="invite",
                    :labels="{ checked: 'Invited', unchecked: 'Not invited' }",
                    :width="105",
                    :height="25")
            div(v-if="!!form.invited_at")
                toggle-button.mb-0(
                    :value="!!form.confirmed_at",
                    :sync="true",
                    :disabled="!form.invited_at",
                    @change="confirm",
                    :labels="{ checked: 'Confirmed', unchecked: 'Unconfirmed' }",
                    :width="120",
                    :height="25")

        .mt-3(v-if="speaker && form.date && form.link && form.name && !form.invited_at")
            label Message
            .bg-light.rounded.p-3(:contenteditable="true", @click="selectText", ref="message"
                @blur="blurred = true")
                p {{ isMale ? 'Querido irmão' : 'Querida irmã' }} {{ speaker.name }}, o bispado da Ala Gama I gostaria de convidar você para discursar por {{ speetchTime }} no dia {{ form.date | date('LL') }} na reunião sacramental. O tema do seu discurso será:
                p {{ form.name }} ({{ form.link }})
                p
                    | Desde já agradecemos por seu apoio e dedicação em servir na casa do Senhor.
                    | "Os anjos falam pelo poder do Espírito Santo; falam, portanto, as palavras de Cristo. Por isto eu vos disse: Banqueteai-vos com as palavras de Cristo; pois eis que as palavras de Cristo vos dirão todas as coisas que deveis fazer." 2 Néfi 32:3
                p.mb-0 Podemos contar com sua ajuda?


        div(slot="footer")
            button.btn.bg-transparent.mr-2(type="button", @click="close") Cancel
            button-loading.btn.btn-primary(:loading="form.submitting" @click="save") Save
</template>

<style lang="sass" scoped>
.vue-js-switch
    font-size: 0.85rem
</style>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    data() {
        return {
            form: new Form(),
            userOptions: [],
            speaker: null,
            blurred: true,
            positionOptions: [
                { id: 1, name: 'First Speaker' },
                { id: 2, name: 'Second Speaker' },
                { id: 3, name: 'Last Speaker' }
            ]
        };
    },

    computed: {
        isMale() {
            return this.speaker && this.speaker.gender === 'male';
        },
        speetchTime() {
            return this.form.position * 5 + ' minutos';
        }
    },

    methods: {
        setup(topic = {}) {
            this.form = new Form(topic || {});
            this.form.user_id && this.fetchUser(this.form.user_id);
            this.speaker = null;
            return this;
        },

        selectText() {
            if (window.getSelection && this.blurred) {
                window.getSelection().selectAllChildren(this.$refs.message);
                this.blurred = false;
            }
        },

        async save() {
            const { data: topic } = await this.form.put(this.$route('api.topics.update', { topic: this.form.id }));
            this.$emit('updated', topic.data);
            this.close();
        },

        async fetchUser(id) {
            const { data: user } = await this.$axios.get(this.$route('api.users.show', { user: id }));
            this.speaker = user.data;
            this.userOptions = [user.data];
        },

        async suggestUsers() {
            this.userOptions = [];
            const { data: users } = await this.$axios.get(this.$route('api.users.suggestions'));
            this.userOptions = users.data;
            this.speaker && this.userOptions.unshift(this.speaker);
        },

        async invite({ value }) {
            this.form.invited_at = value;
        },

        async confirm({ value }) {
            this.form.confirmed_at = value;
        }
    }
};
</script>
