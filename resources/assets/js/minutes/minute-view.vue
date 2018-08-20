<template lang="pug">
    div.text-bigger.mb-5.pb-3
        h1.text-center.text-primary.mb-4
            | Ata da Reunião Sacramental
            br
            small.text-muted {{ minute.date | date }}


        .row.mb-4.justify-content-center
            .col-auto.mw-100
                p
                    b Ala:
                    span  {{ minute.ward }}
            .col-auto.mw-100
                p
                    b Estaca:
                    span  {{ minute.stake }}
            .col-auto.mw-100
                p
                    b Presidida:
                    span  {{ minute.presided_by }}
            .col-auto.mw-100
                p
                    b Dirigida:
                    span  {{ minute.directed_by }}
            .col-auto.mw-100
                p
                    b Regente:
                    span  {{ minute.conductor }}
            .col-auto.mw-100
                p
                    b Pianista:
                    span  {{ minute.pianist }}

        .row.mb-4
            .col-auto.mw-100
                p.mb-1
                    b Boas Vindas:
                    span  {{ minute.welcome }}
                p.mb-1
                    b Anúncios:
                    span  {{ minute.announcement }}
                p.mb-1
                    b Hino de Abertura:
                    span  {{ minute.first_hymn }}

        divisor.mb-4

        div.mb-4(v-if="hasAnyAction")
            minute-table(title="Chamados", v-if="hasCallings", :value="minute.callings")
            minute-table(title="Confirmações", v-if="hasConfirmations", :value="minute.confirmations")
            minute-table(title="Ordenanção ao Sacerdócio", v-if="hasOrdinances", :value="minute.ordinances")
            minute-table(title="Bênção de Crianças", v-if="hasBabyBlessings", :value="minute.baby_blessings")

        p.mb-4
            b Hino Sacramental:
            span  {{ minute.sacrament_hymn }}

        divisor.mb-4

        h2.section-title Mensagem
        p.mb-0
            b Primeiro Orador:
            span  {{ minute.first_speaker }}
        p.mb-0
            b Segundo Orador:
            span  {{ minute.second_speaker }}
        p.mb-4
            b.text-primary Interlúdio Musical:
            span  {{ minute.intermediate_hymn }}

        divisor.mb-4

        h2.section-title Encerramento

        p.mb-0
            b Terceiro Orador:
            span  {{ minute.third_speaker }}
        p.mb-0
            b Último Hino:
            span  {{ minute.last_hymn }}
        p.mb-0
            b Última Oração:
            span  {{ minute.last_prayer }}

        .card.fixed-bottom.rounded-0.d-print-none.border-top.border-right-0.border-bottom-0.border-left-0
            .card-body
                .container.p-0.p-md-3
                    .btn-group.mr-2
                        a.btn.btn-default(:href="prev")
                            i.fa.fa-angle-double-left.mr-2
                            span.d-none.d-md-inline  Anterior
                        a.btn.btn-default(:href="next")
                            span.d-none.d-md-inline Próxima
                            i.fa.fa-angle-double-right.ml-2
                    a.btn.btn-primary.float-right(
                        :href="$route('minutes.form', { minute: minute.id })")
                        i.fa.fa-pencil-alt
                        span.ml-2 Editar
                    a.btn.btn-default.float-right.mr-2(href="#", @click.prevent.stop="print")
                        i.fa.fa-print
                        span.ml-2.d-none.d-md-inline Imprimir

</template>

<style lang="sass" scoped>
.text-bigger
    font-size: 1.2rem
</style>


<script>
export default {
    props: {
        minute: { required: true, type: Object },
    },
    created() {
        console.log('created', this.minute);
    },
    computed: {
        next() {
            return this.$route(
                'minutes.next', { from: moment(this.minute.date).format('YYYY-MM-DD')
            });
        },
        prev() {
            return this.$route(
                'minutes.prev', { from: moment(this.minute.date).format('YYYY-MM-DD')
            });
        },
        hasAnyAction() {
            return this.hasCallings
                || this.hasConfirmations
                || this.hasBabyBlessings
                || this.hasOrdinances;
        },
        hasCallings() { return this.minute.callings && this.minute.callings.length; },
        hasConfirmations() { return this.minute.confirmations && this.minute.confirmations.length; },
        hasBabyBlessings() { return this.minute.baby_blessings && this.minute.baby_blessings.length; },
        hasOrdinances() { return this.minute.ordinances && this.minute.ordinances.length; },
    },
    methods: {
        print() {
            window.print();
        }
    },
}
</script>
