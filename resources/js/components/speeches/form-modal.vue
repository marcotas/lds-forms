<template lang="pug">
    modal(ref="modal")
        h5(slot="title") Discurso

        .card.mb-3(v-if="form.image_url")
            .card-body.p-3.d-flex.align-items-center
                img.mr-3.rounded(:src="form.image_url", v-if="form.image_url", style="max-width: 100px")
                .mr-3
                    a.lead(:href="form.link", target="_blank") {{ form.title }}
                    .text-muted {{ form.author }}

        .card.bg-transparent.border-0
            ul.nav.nav-tabs
                li.nav-item
                    a.nav-link(href="#details", data-toggle="tab", :class="{ active: isActive('details') }", @click="tab = 'details'")
                        i.fa.fa-info.mr-1
                        |  Detalhes
                li.nav-item(v-if="canInvite")
                    a.nav-link(href="#invite", data-toggle="tab", :class="{ active: isActive('invite') }", @click="tab = 'invite'")
                        i.fab.fa-whatsapp.mr-1
                        |  Convite
            .tab-content.bg-white.rounded-bottom.shadow-sm
                #details.tab-pane.p-3.fade(:class="{ 'show active': isActive('details') }")
                    input-text(v-model="form.title", field="title", :form="form", label="Título")
                    label
                        span(:class="{'text-danger': form.errors.has('link') }") URL do Discurso
                        a.ml-2(:href="form.link", target="_blank", v-if="form.link", v-tooltip="{title: 'Abrir Link', placement: 'right'}")
                            i.fa.fa-external-link-alt
                    input-text(v-model="form.link", field="link", :form="form",
                        placeholder="Exemplo: https://www.lds.org/general-conference...")

                    input-text(v-model="form.author", field="author", :form="form", label="Autor")

                    input-text(type="date", v-model="form.date", field="date", :form="form", label="Data")

                    input-select(v-model="form.order", field="order", :form="form", label="Ordem", :options="orders")
                    label Duração
                        small.text-muted.ml-1 (minutos)
                    input-text(type="number", step="5", v-model="form.duration", field="duration", :form="form", min="5")

                    .form-group
                        label.form-label(:class="{'text-info': isInvited && !isConfirmed, 'text-success': isConfirmed}") Discursante
                        input-suggestions(:form="form", field="speaker_id", ref="speakerSuggestions"
                            placeholder="Procure um discursante",
                            left-icon="fa fa-user",
                            :options="speakers",
                            :allow-create="true",
                            :loading="fetchingSpeakers",
                            @search="onSearchSpeakers",
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
                        p.d-flex.align-items-center.t-text-xs(:class="{'text-info': isInvited && !isConfirmed, 'text-success': isConfirmed}")
                            span.t-text-xs(v-if="isInvited && !isConfirmed")
                                i.mr-1.fa.fa-check
                                | Convidado por {{ invitedBy.name }}
                            span.t-text-xs(v-if="isConfirmed")
                                i.mr-1.fa.fa-check-double
                                | Confirmado por {{ confirmedBy.name }}

                    b-form-checkbox(@input="form.invited_at = $event" :checked="!!form.invited_at" switch) Convidado(a)
                    transition(name="fade")
                        b-form-checkbox(v-if="form.invited_at" @input="form.confirmed_at = $event" :checked="!!form.confirmed_at" switch) Confirmado(a)

                #invite.tab-pane.p-3.fade(v-if="canInvite", :class="{ 'show active': isActive('invite') }")
                    .mb-3
                        button.btn.btn-secondary.btn-sm(@click="copyToClipboard")
                            i.fa.fa-copy.mr-2
                            | Copiar mensagem
                    div(ref="message")
                        | {{ speaker.gender === 'male' ? 'Querido irmão' : 'Querida irmã' }} {{ speaker.name }}, o bispado da Ala {{ $team.name }} gostaria de convidar você para discursar por {{ form.duration }} minutos no dia {{ form.date | date }} na reunião sacramental. O tema do seu discurso será:
                        br
                        br
                        | {{ form.title }} ({{ form.link }})
                        br
                        br
                        | Desde já agradecemos por seu apoio e dedicação em servir na casa do Senhor.
                        br
                        br
                        | "Os anjos falam pelo poder do Espírito Santo; falam, portanto, as palavras de Cristo. Por isto eu vos disse: Banqueteai-vos com as palavras de Cristo; pois eis que as palavras de Cristo vos dirão todas as coisas que deveis fazer."
                        br
                        | 2 Néfi 32:3
                        br
                        br
                        | Podemos contar com sua ajuda?



        .d-flex.w-100(slot="footer")
            button.btn.btn-default.mr-2(v-if="!form.id", @click="onSelectClick") Selecionar
            button.btn.btn-outline-danger.mr-2(v-if="form.id", @click="confirmRemove")
                i.fa.fa-trash-alt
                span.ml-2 Remover
            button.btn.btn-default.mr-2.ml-auto(@click="close") Cancelar
            button-loading.btn.btn-primary(@click="save", :loading="form.submitting") Salvar

        confirmation(ref="confirmation", :dangerous="true")
</template>

<style lang="sass" scoped>
.nav-link.active
    background: white !important
    border-color: white !important
</style>


<script>
    import ModalComponent from '@/mixins/modal-component';
    import _ from 'lodash';

    export default {
        mixins: [ModalComponent],

        data() {
            return {
                form: new Form({
                    date: new Date(),
                    duration: 15,
                }),
                tab: 'details',
                speakers: [],
                speaker: null,
                editing: false,
                fetchingSpeakers: false,
                creatingUser: false,
                removing: false,
                orders: [{ id: 1, name: '1º Orador' }, { id: 2, name: '2º Orador' }, { id: 3, name: '3º Orador' }],
            };
        },

        computed: {
            canInvite() {
                return this.speaker && this.form.date && this.form.duration;
            },

            isConfirmed() {
                return this.form.invited_at && this.form.confirmed_at;
            },

            isInvited() {
                return this.form.invited_at;
            },

            confirmedBy() {
                return this.form.confirmed_by || this.$user;
            },

            invitedBy() {
                return this.form.invited_by || this.$user;
            },
        },

        watch: {
            'form.speaker_id'() {
                this.speaker = this.speakers.find(user => user.id === this.form.speaker_id);
            },
        },

        methods: {
            setup(speech = {}) {
                this.form = new Form(speech);
                this.speakers = (speech.speaker && [speech.speaker]) || [];
                this.$set(this.form, 'order', this.form.order || 1);
                this.$set(this.form, 'duration', this.form.duration || 15);
                this.$set(this.form, 'link', this.form.link || null);
                this.$set(this.form, 'title', this.form.title || null);
                this.fetchSpeakers();
                this.editing = false;
                this.tab = 'details';
                return this;
            },

            onSelectClick() {
                this.close();
                this.$emit('clickSelect', this.form);
            },

            onSearchSpeakers: _.debounce(async function(search) {
                this.fetchSpeakers(search);
            }, 500),

            isActive(tab) {
                return this.tab === tab;
            },

            async fetchSpeakers(search) {
                try {
                    this.fetchingSpeakers = true;
                    const params = { per_page: 100, search };
                    const { data: speakers } = await this.$axios.get(route('members.index'), { params });
                    this.speakers = speakers.data;
                } catch (error) {
                    console.log('error', error);
                } finally {
                    this.fetchingSpeakers = false;
                }
            },

            async save() {
                try {
                    const { data: speech } = await this.form.save(route('speeches.store'));
                    this.$toasted.show('Discurso salvo com sucesso');
                    this.$emit('saved', speech);
                    this.close();
                } catch (error) {
                    console.log('error', error);
                }
            },

            async createMember(name) {
                try {
                    this.creatingUser = true;
                    const { data: user } = await this.$axios.post(route('members.store'), { name });
                    this.speakers = [user.data];
                    this.form.speaker_id = user.data.id;
                    this.$refs.speakerSuggestions.select(user.data);
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

            async copyToClipboard() {
                let element = this.$refs.message;
                let { text } = await this.$copyText(element.innerText || element.textContent, this.$el);
                this.$toasted.show('Convite copiado', { icon: 'fa-check-double' });
            },
        },
    };
</script>
