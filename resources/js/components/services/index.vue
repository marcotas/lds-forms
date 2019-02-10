<template lang="pug">
    .my-4
        h3.mb-4 Serviços

        .d-flex.mb-4
            input-text.mb-0.mr-auto(v-model="search", input-class="border-0 shadow-sm" placeholder="Procurar serviço...", left-icon="fa fa-search")
            button.btn.btn-primary(@click="addService")
                i.fa.fa-plus.mr-md-2
                span.d-none.d-sm-inline Adicionar Serviço

        service-form(ref="modal", @saved="onSave", :team-id="$team.id")

        .card.border-0.shadow-sm
            resource-table.border-0(ref="table"
                url="/services",
                :search="search",
                :columns="columns",
                :options="tableOptions",
                :can-edit="false"
                :has-bulk-delete="false"
                :has-bulk-actions="false"
                :can-show="false"
                default-sort="name")

                template(slot="more-actions", slot-scope="{ resource: service }")
                    button.btn.btn-sm.bg-transparent(@click="edit(service)")
                        i.far.fa-edit.text-black-50

                template(slot="name", slot-scope="{resource: service}")
                    span(v-tooltip="{title: service.description, boundary: 'window'}") {{ service.name | capitalize }}
                template(slot="commission", slot-scope="{resource: service}")
                    span {{ service.commission }}%
                template(slot="price", slot-scope="{resource: service}")
                    span R$ {{ service.price.toFixed(2) }}
</template>

<script>
import ServiceForm from '@/components/services/form';

export default {
    components: { ServiceForm },

    data() {
        return {
            search: null,
            columns: ['name', 'commission', 'price'],
            tableOptions: {
                sortable: ['name', 'commission', 'price'],
                headers: {
                    name: 'Nome',
                    commission: 'Comissão',
                    price: 'Preço',
                },
                filters: [
                    { label: 'Mostrar Arquivados', field: 'withTrashed', value: true },
                    { label: 'Somente Arquivados', field: 'onlyTrashed', value: true },
                ],
            },
        };
    },

    methods: {
        addService() {
            this.$refs.modal.open().setup();
        },

        edit(service) {
            this.$refs.modal.open().setup(service);
        },

        onSave(service) {
            this.$toasted.success('Serviço salvo com successo');
            this.$refs.table.refresh();
        },
    },
};
</script>
