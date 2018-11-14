<template lang="pug">
    modal(ref="modal")
        span(slot="title") Topic
        input-text(label="Name", v-model="form.name", :form="form", field="name")
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

        div(v-if="speaker && form.date && form.link && form.name")
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

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    data() {
        return {
            form: new Form(),
            userOptions: [],
            speaker: null,
            blurred: true
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
        }
    }
};
</script>
