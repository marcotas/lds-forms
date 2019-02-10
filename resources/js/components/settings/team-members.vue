<template lang="pug">
    .card.border-0.shadow-sm
        .card-body
            h5.card-title.mb-0 Membros ({{ team.users.length }})
        .table-responsive
            table.table.mb-0
                thead.bg-grey
                    th
                    th Nome
                    th Email
                    th Perfil
                    th
                tbody
                    tr(v-for="user of team.users", :key="user.id")
                        td.align-middle
                            avatar(:username="user.name", :src="user.photo_url", :size="35")
                        td.align-middle
                            span {{ user.name }}
                            span(v-if="$user.id === user.id")  (Você)
                        td.align-middle {{ user.email }}
                        td.align-middle {{ roleOnTeam(user).label | capitalize }}
                        td.align-middle
                            button.btn.btn-sm.btn-danger(v-tooltip="'Remover Membro'", v-if="user.id !== $user.id")
                                i.fa.fa-fw.fa-sign-out-alt
</template>

<script>
import resource from '@/plugins/resource';
import { laroute } from '@/plugins';

export default {
    props: {
        team: Object,
    },

    created() {
        console.log('team members', this.team);
    },

    methods: {
        roleOnTeam(user) {
            return user.roles.find(role => role.team_id === this.team.id) || {};
        },
    },
};
</script>
