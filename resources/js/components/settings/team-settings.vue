<template lang="pug">
    .my-4
        .card.border-0.shadow-sm
            .card-body
                h5.card-title.mb-3 Informações

                input-text.row(label="Nome da Ala", v-model="form.name", input-wrapper-class="col-md-6", label-class="col-md-3 col-form-label text-md-right")

                .row
                    .col-md-3.offset-3
                        button-loading.btn.btn-primary(@click="save", :loading="form.submitting")
                            i.far.fa-fw.fa-save(v-if="!form.submitting")
                            span  Salvar
        .mt-4
            team-members(:team="team")
</template>

<script>
import { Form } from '@elitedevsquad/ui';
import resource from '@/plugins/resource';
import { laroute } from '@/plugins';
import axios from 'axios';
import TeamMembers from './team-members';

export default {
    components: { TeamMembers },

    props: {
        team: Object,
    },

    data() {
        return {
            form: new Form(this.team),
        };
    },

    methods: {
        async save() {
            const { data: team } = await this.form.save(route('teams.index'));

            this.$toasted.success('Ala atualizada com sucesso.');
        },
    },
};
</script>
