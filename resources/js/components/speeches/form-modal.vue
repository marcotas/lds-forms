<template lang="pug">
    modal(ref="modal")
        h5(slot="title") Discurso

        .card.mb-3(v-if="form.image_url")
            .card-body.p-3.d-flex.align-items-center
                img.mr-3.rounded(:src="form.image_url", v-if="form.image_url", style="max-width: 100px")
                .mr-3
                    a.lead(:href="form.link", target="_blank") {{ form.title }}
                    .text-muted {{ form.author }}

        input-text(v-model="form.title", field="title", :form="form", label="Título")
        label
            span Link
            a.ml-2(:href="form.link", target="_blank", v-if="form.link", v-tooltip="{title: 'Abrir Link', placement: 'right'}")
                i.fa.fa-external-link-alt
        input-text(v-model="form.link", field="link", :form="form")

        input-text(v-model="form.author", field="author", :form="form", label="Autor")

        input-text(type="date", v-model="form.date", field="date", :form="form", label="Data")

        .form-group
            label.form-label Discursante
            input-suggestions(:form="form", field="speaker_id", ref="speakerSuggestions"
                placeholder="Procure um discursante",
                left-icon="fa fa-user",
                :options="speakers",
                :allow-create="true",
                :loading="fetchingSpeakers",
                @search="fetchSpeakers",
                @create="createMember"
                v-model="form.speaker_id")
                template(v-slot:option="{ item: user }")
                    .d-flex.align-items-center
                        div
                            div {{ user.name }}
                            .t--mt-1.text-black-50.t-text-xs(v-if="user.email") {{ user.email }}
                        .ml-auto.badge.badge-secondary(v-if="user.is_user") Usuário
                template(v-slot:footer="{ search }")
                    .d-flex.align-items-center
                        span Adicionar
                            b  {{ search }}
                        .ml-auto(v-if="creatingUser")
                            spinner(width="1rem")

        input-select(v-model="form.order", field="order", :form="form", label="Ordem", :options="orders")
        label Duração
            small.text-muted.ml-1 (minutos)
        input-text(type="number", step="5", v-model="form.duration", field="duration", :form="form", min="5")

        .d-flex.w-100(slot="footer")
            button.btn.btn-default.mr-2(v-if="!form.id", @click="onSelectClick") Selecionar
            button.btn.btn-outline-danger.mr-2(v-if="form.id", @click="confirmRemove")
                i.fa.fa-trash-alt
                span.ml-2 Remover
            button.btn.btn-default.mr-2.ml-auto(@click="close") Cancelar
            button-loading.btn.btn-primary(@click="save", :loading="form.submitting") Salvar

        confirmation(ref="confirmation", :dangerous="true")
</template>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    data() {
        return {
            form: new Form({
                date: new Date(),
            }),
            speakers: [],
            editing: false,
            fetchingSpeakers: false,
            creatingUser: false,
            removing: false,
            orders: [{ id: 1, name: '1º Orador' }, { id: 2, name: '2º Orador' }, { id: 3, name: '3º Orador' }],
        };
    },

    methods: {
        setup(speech = {}) {
            this.form = new Form(speech);
            this.speakers = (speech.speaker && [speech.speaker]) || [];
            this.form.order = this.form.order || 1;
            this.form.duration = this.form.duration || 15;
            this.fetchSpeakers();
            this.editing = false;
            return this;
        },

        onSelectClick() {
            this.close();
            this.$emit('clickSelect', this.form);
        },

        async fetchSpeakers(search) {
            try {
                this.fetchingSpeakers = true;
                const params = { per_page: 100, search };
                const { data: users } = await this.$axios.get(route('members.index'), { params });
                this.speakers = users.data;
            } catch (error) {
                console.log('error', error);
            } finally {
                this.fetchingSpeakers = false;
            }
        },

        async save() {
            const { data: speech } = await this.form.save(route('speeches.store'));
            this.$toasted.show('Discurso salvo com sucesso');
            this.$emit('saved', speech);
            this.close();
        },

        async createMember(name) {
            try {
                this.creatingUser = true;
                const { data: user } = await this.$axios.post(route('members.store'), { name });
                this.speakers = [user];
                this.form.speaker_id = user.id;
                this.$refs.speakerSuggestions.select(user);
                this.$refs.speakerSuggestions.close();
            } finally {
                this.creatingUser = false;
            }
        },

        async confirmRemove() {
            try {
                const { value: confirmed } = await this.$swal({
                    title: 'Tem certeza?',
                    text: 'Isto não poderá ser desfeito!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: this.$dangerColor,
                    confirmButtonText: 'Sim, apagar!',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: this.remove.bind(this),
                });

                if (confirmed) {
                    await this.$swal({
                        title: 'Sucesso!',
                        text: 'Discurso removido com sucesso.',
                        type: 'success',
                    });

                    this.close();
                    this.$emit('removed');
                }
            } catch (error) {
                console.log('error', error, error.response);
                this.$swal({
                    title: 'Oops!',
                    text: 'Ocorreu um erro desconhecido. Por favor tente mais tarde',
                    type: 'error',
                });
            }
        },

        async remove() {
            await this.$axios.delete(this.route('speeches.destroy', this.form));
        },
    },
};
</script>
