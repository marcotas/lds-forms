<template lang="pug">
    div.mb-5.pb-3
        h1.text-center.mb-4
            | Ata da Reunião Sacramental
            br
            small.text-muted {{ form.date | date }}

        .card.mb-4
            .card-body
                .row
                    .col-sm-12.col-md-3
                        .form-group
                            label.form-label Data
                            v-date-picker(
                                v-model="form.date",
                                :input-props="{class: 'form-control'}")
                    .col-sm-12.col-md-3
                        input-text(label="Estaca", :form="form", field="stake", v-model="form.stake")
                    .col-sm-12.col-md-3
                        input-text(label="Ala", :form="form", field="ward", v-model="form.ward")
                    .col-sm-12.col-md-3
                        input-text(label="Frequência", :form="form", field="attendance", v-model="form.attendance")
                .row
                    .col-sm-12.col-md-3
                        input-text.mb-0(label="Presidida", :form="form", field="presided_by", v-model="form.presided_by")
                    .col-sm-12.col-md-3
                        input-text.mb-0(label="Dirigida", :form="form", field="directed_by", v-model="form.directed_by")
                    .col-sm-12.col-md-3
                        input-text.mb-0(label="Regente", :form="form", field="conductor", v-model="form.conductor")
                    .col-sm-12.col-md-3
                        input-text.mb-0(label="Pianista", :form="form", field="pianist", v-model="form.pianist")

        h2 Abertura
        .card.mb-4
            .card-body
                input-text(label="Boas Vindas", :form="form", field="welcome", v-model="form.welcome")
                input-text(label="Anúncios", :form="form", field="announcement", v-model="form.announcement")
                .row
                    .col-sm-12.col-md-6
                        input-text(label="Hino", :form="form", field="first_hymn", v-model="form.first_hymn")
                    .col-sm-12.col-md-6
                        input-text(label="Primeira Oração", :form="form", field="first_prayer", v-model="form.first_prayer")

        h2 Ações
        minute-table-form.mb-4(title="Chamados", v-model="form.callings", :columns="callingsColumns")
        minute-table-form.mb-4(title="Confirmações", v-model="form.confirmations", :columns="confirmationsColumns")
        minute-table-form.mb-4(title="Ordenações ao Sacerdócio", v-model="form.ordinances", :columns="ordinancesColumns")
        minute-table-form.mb-4(title="Bênção de Crianças", v-model="form.baby_blessings", :columns="babyBlessingsColumns")

        h2 Sacramento
        .card.mb-4
            .card-body
                input-text.mb-0(label="Hino Sacramental", :form="form", field="sacrament_hymn", v-model="form.sacrament_hymn")
                p.text-success.mt-3.mb-0 Bênção e Distribuição do Sacramento


        h2 Mensagem
        .card.mb-4
            .card-body
                input-text(label="Primeiro Orador", :form="form", field="first_speaker", v-model="form.first_speaker")
                input-text.mb-0(label="Segundo Orador", :form="form", field="second_speaker", v-model="form.second_speaker")
            .bg-light.border-top.border-bottom(style="padding: 1.25rem")
                input-text.mb-0(label="Interlúdio Musical", :form="form", field="intermediate_hymn", v-model="form.intermediate_hymn")
            .card-body
                input-text.mb-0(label="Terceiro Orador", :form="form", field="third_speaker", v-model="form.third_speaker")


        h2 Encerramento
        .card.mb-4
            .card-body
                input-text(label="Último Hino", :form="form", field="last_hymn", v-model="form.last_hymn")
                input-text.mb-0(label="Última Oração", :form="form", field="last_prayer", v-model="form.last_prayer")

        .card.fixed-bottom.rounded-0.d-print-none
            .card-body
                .container
                    .btn-group.mr-2
                        a.btn.btn-default(:href="prev")
                            i.fa.fa-angle-double-left.mr-2
                            span.d-none.d-md-inline  Anterior
                        a.btn.btn-default(:href="next")
                            span.d-none.d-md-inline Próxima
                            i.fa.fa-angle-double-right.ml-2
                    button-loading.btn.btn-success.float-right(@click="save", :loading="form.submitting")
                        i.fa.fa-save(v-if="!form.submitting")
                        span.ml-2 Salvar
                    a.btn.btn-default.float-right.mr-2(
                        :href="$route('minutes.show', { minute: form.id })")
                        i.fa.fa-eye
                        span.ml-2.d-none.d-md-inline Visualizar

</template>

<script>
export default {
    props: {
        minute: { required: true, type: Object }
    },
    data() {
        return {
            form: new Form({
                ...this.minute,
                date: new Date(this.minute.date)
            }),

            callingsColumns: [
                { name: "Nome", style: { width: "40%" } },
                { name: "A/D", style: { width: "10%" } },
                { name: "Chamado", style: { width: "40%" } },
            ],
            confirmationsColumns: [
                { name: "Nome", style: { width: "45%" } },
                { name: "Confirmado Por", style: { width: "45%" } },
            ],
            ordinancesColumns: [
                { name: "Nome", style: { width: "35%" } },
                { name: "Sacerdócio", style: { width: "10%" } },
                { name: "Ofício", style: { width: "10%" } },
                { name: "Ordenado Por", style: { width: "35%" } },
            ],
            babyBlessingsColumns: [
                { name: "Nome", style: { width: "35%" } },
                { name: "Data de Nascimento", style: { width: "20%" } },
                { name: "Abençoado(a) Por", style: { width: "35%" } },
            ],
        };
    },
    computed: {
        next() {
            return this.$route(
                'minutes.nextForm', { from: moment(this.form.date).format('YYYY-MM-DD')
            });
        },
        prev() {
            return this.$route(
                'minutes.prevForm', { from: moment(this.form.date).format('YYYY-MM-DD')
            });
        },
    },
    methods: {
        async save() {
            try {
                const { data } = await this.form.put(
                    this.$route("api.minutes.update", { minute: this.form.id })
                );
                this.$toasted.show("Ata salva com sucesso.", { icon: "check" });
            } catch (e) {
                this.$toastr.error(
                    "Oops! Não foi possível completar essa operação.",
                    "Error"
                );
                console.log("error", e);
            }
        },
    }
};
</script>
