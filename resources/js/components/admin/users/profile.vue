<template lang="pug">
    div
        .card.border-0.shadow-sm.mb-3
            .card-body(v-if="user")
                h5.card-title.mb-3
                    span {{ user.name }}
                    a.float-right(@click="$router.go(-1)")
                        i.fa.fa-fw.fa-times.text-black-50.cursor-pointer
                .d-flex.align-items-center
                    avatar.m-2(:username="user.name", :size="100", :src="user.photo", color="white")
                    div.ml-4
                        div
                            b Email:
                            span  {{ user.email }}
                        div
                            b Desde:
                            span  {{ user.created_at | date('LLLL') }}
            .card-footer(v-if="user")
                a.btn.btn-default.float-right(:href="$laroute('admin.users.impersonate', { user: user.id })")
                    i.fa.fa-user-secret.mr-2
                    span  Personificar
                a.btn.btn-default.float-right.mr-2(:href="$laroute('users.edit', { user: user.id })")
                    i.fa.fa-pencil-alt.mr-2
                    span  Editar

        b-card.border-0.shadow-sm(no-body, v-if="user")
            b-tabs(card)
                b-tab.mb-0.pt-1(title="Alas", title-link-class="white")
                    .d-flex.align-items-center.mt-3(v-for="team of user.teams", :key="team.id")
                        avatar(:username="team.name", :src="team.photo_url", color="white", :size="35")
                        .ml-3
                            b Nome:
                            span  {{ team.name }}
                        .ml-3
                            b Perfil:
                            span  {{ roleOnTeam(team).label | capitalize }}

                b-tab(title="Permissões", title-link-class="white")
                    permission-list.mb-0(:removable="false", :permissions="user.permissions")
</template>

<script>
import axios from 'axios';
import { laroute } from '@/plugins';
import resource from '@/plugins/resource';
import PermissionList from '@/components/permissions/list';

export default {
    components: { PermissionList },

    data() {
        return {
            user: null,
        };
    },

    async beforeRouteEnter(to, from, next) {
        const user = await resource(laroute('users.index')).find(to.params.id);
        next(vm => (vm.user = user));
    },

    methods: {
        roleOnTeam(team) {
            return this.user.roles.find(role => role.team_id === team.id) || {};
        },
    },
};
</script>
