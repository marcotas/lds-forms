<template lang="pug">
    modal(ref="modal")
        span(slot="title") Importar Discursos
        div(v-if="!form.speeches.length")
            input-text(v-model="form.link", :form="form", field="link", label="Link da última conferência geral",
                placeholder="Cole aqui o link da última conferência geral")
            button-loading.btn.btn-block.btn-secondary(:loading="form.submitting",
                @click="fetchSpeeches") Listar Discursos do Link

        div(v-if="form.speeches.length")
            .mb-2 Total de discursos: {{ form.speeches.length }}
            transition-group(name="flip-list")
            speech-card(v-for="speech of form.speeches", :key="speech.link", :speech="speech",
                @remove="remove")

        .w-100.d-flex(slot="footer")
            button.btn.btn-default.mr-auto(@click="clear") Limpar Todos
            button.btn.btn-default.ml-2(@click="close") Cancelar
            button-loading.btn.btn-primary.ml-2(@click="importAll", :loading="importing") Importar ({{ form.speeches.length }})
</template>

<style lang="sass" scoped>
.list-item
    display: inline-block
    margin-right: 10px

.list-enter-active, .list-leave-active
    transition: all 1s

.list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */
    opacity: 0
    transform: translateY(30px)

.flip-list-move
    transition: transform 1s

</style>


<script>
import ModalComponent from '@/mixins/modal-component';
import SpeechCard from '@/components/speeches/speech-card';

export default {
    mixins: [ModalComponent],

    components: {
        SpeechCard,
    },

    data() {
        return {
            form: new Form({ link: 'https://www.lds.org/general-conference?lang=por', speeches: [] }),
            importing: false,
        };
    },

    methods: {
        async fetchSpeeches() {
            const { data: speeches } = await this.form.get(route('speeches.get-from-conference', { link: this.form.link }));
            this.form.speeches = speeches;
        },

        async importAll() {
            try {
                this.importing = true;
                const { data: speeches } = await this.form.post(route('speeches.import-all'));
                this.$emit('imported');
                this.close();
                this.$toasted.show('Discursos importados com sucesso');
            } finally {
                this.importing = false;
            }
        },

        remove(speech) {
            this.form.speeches.splice(this.form.speeches.indexOf(speech), 1);
        },

        clear() {
            this.form.speeches = [];
        },

        afterOpen() {
            this.clear();
        },
    },
};
</script>
