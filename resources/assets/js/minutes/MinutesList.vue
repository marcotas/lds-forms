<template lang="pug">
div
    h1
        | Atas Sacramentais
        a.btn.btn-primary.float-right(:href="$route('minutes.next')") Ata do Próximo Domingo
    .card
        table.table
            thead
                tr
                    th.border-top-0.border-bottom-0 Número
                    th.border-top-0.border-bottom-0 Data
                    th.border-top-0.border-bottom-0 Frequência
            tbody
                tr(v-for="minute of minutes.data", :key="minute.id")
                    td
                        a(:href="$route('minutes.show', { minute: minute.id })") {{ minute.id }}
                    td
                        a(:href="$route('minutes.show', { minute: minute.id })")
                            | {{ minute.date | date }}
                            .badge.badge-primary.ml-2(v-if="isNextSunday(minute.date)") Próximo Domingo
                    td {{ minute.attendance }}
                    td
                        button-loading.btn.btn-sm.btn-danger(
                            @click="remove(minute)",
                            :loading="isRemoving(minute)")
                            i.fa.fa-trash(v-if="!isRemoving(minute)")
        pagination.border-top.pt-3(:meta="minutes.meta", :links="minutes.links", @page="fetchMinutes")
</template>

<script>
export default {
    data() {
        return {
            minutes: {
                data: [],
                meta: null,
                links: null,
                next_sunday: null,
            },
            removing: [],
            page: 1,
        };
    },
    created() {
        this.fetchMinutes();
    },
    methods: {
        async fetchMinutes(page = 1) {
            this.page = page;
            const params = { page };
            const { data: minutes } = await http
                .get(route('api.minutes.index'), { params });

            return this.minutes = minutes;
        },
        async remove(minute) {
            this.removing.push(minute.id);
            await http.delete(
                route('api.minutes.destroy', { minute: minute.id })
            );
            this.$toasted.show('Ata removida com sucesso');
            this.fetchMinutes(this.page);
        },
        isNextSunday(date) {
            date = moment(date).format('YYYY-MM-DD');
            return this.minutes.next_sunday === date;
        },
        isRemoving(minute) {
            return this.removing.includes(minute.id);
        },
    }
};
</script>
