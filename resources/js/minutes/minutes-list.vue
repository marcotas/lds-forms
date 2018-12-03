<template lang="pug">
div
    h3.clearfix Atas Sacramentais

    resource-table(
        ref="table"
        model-type="minute",
        :columns="['id', 'date', 'attendance']",
        default-sort="-date",
        :options="table",
    )
        template(slot="top-actions")
            a.btn.btn-primary.float-right.mb-3(:href="$route('minutes.next')") Ata do Próximo Domingo

        template(slot="date", slot-scope="prop")
            a(:href="$route('minutes.show', { minute: prop.row.id })")
                | {{ prop.row.date | date }}
                .badge.badge-primary.ml-2(v-if="isNextSunday(prop.row.date, prop)") Próximo Domingo
</template>

<script>
export default {
    data() {
        return {
            table: {
                headers: {
                    id: 'ID',
                    date: 'Data',
                    attendance: 'Frequência'
                },
                sortable: ['id', 'date']
            }
        };
    },
    methods: {
        isNextSunday(date) {
            date = moment(date).format('YYYY-MM-DD');
            return this.$refs.table.$data.resources.next_sunday === date;
        }
    }
};
</script>
