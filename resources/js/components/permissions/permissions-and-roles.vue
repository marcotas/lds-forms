<template lang="pug">
    .row
        .col-md-6(v-if="user")
            permission-select-modal(ref="addPermissionModal", :permissions="allPermissions", :teams="user.teams"
                @selected="onSelectedPermissions")

            h4.d-flex.align-items-center.mb-3
                | Permissões Específicas
                button.ml-2.btn.btn-primary.btn-sm(@click="addPermission") Adicionar

            permission-list.mb-4(:permissions="global_permissions", @remove="removePermission", key="global")
            permission-list.mb-4(v-for="team of teams", :key="team.id", :team="team", :permissions="specificPermissionsOf(team)",
                @remove="removePermission", key="team")

        .col-md-6.mt-4.mt-md-0(v-if="user")
            h4.d-flex.align-items-center.mb-3(v-if="roles.length")
                | Perfis do Usuário
                //- button.ml-2.btn.btn-primary.btn-sm(@click="addPermission") Adicionar

            .pt-4(v-if="!roles.length")
                empty.my-3(type="swipe-profiles", opacity="0.8", width="250px" title="Nenhum perfil para este usuário")
                    button.mt-2.btn.btn-primary.btn-lg(@click="addPermission")
                        i.fa.fa-fw.fa-user-circle.mr-2
                        span Adicionar Perfil

            permission-list.mb-3(v-for="role of roles", :key="role.id", :role="role", :removable="false", key="roles")

        .col-12
            button-loading.btn.btn-success(@click="saveSpecificPermissions", :loading="form.submitting")
                | Salvar Permissões
</template>

<script>
import PermissionCard from './card';
import PermissionSelectModal from './select-modal';
import PermissionList from './list';

export default {
    components: { PermissionCard, PermissionSelectModal, PermissionList },

    props: {
        allPermissions: { required: true, type: Array },
        user: { required: true, type: Object },
    },

    data() {
        return {
            permissions: this.allPermissions,
            roles: this.user ? this.user.roles || [] : [],
            specific_permissions: this.user ? this.user.specific_permissions || [] : [],
            teamMap: {},
            teams: (this.user && this.user.teams) || [],
            form: new Form(this.user),
        };
    },

    created() {
        this.$set(this.user, 'roles', this.user.roles || []);
        this.$set(this.user, 'specific_permissions', this.user.specific_permissions || []);
    },

    computed: {
        global_permissions() {
            return this.specific_permissions.filter(perm => !perm.pivot.team_id);
        },
    },

    methods: {
        onRemove(permission) {
            this.specific_permissions = this.specific_permissions.filter(perm => perm.id !== permission.id);
        },

        addPermission() {
            this.$refs.addPermissionModal.open();
        },

        async onSelectedPermissions(permissions) {
            const diff = permissions.filter(perm => !this.hasSpecificPermission(perm));
            let teamId;
            if ((teamId = this.getTeamIdFromDiff(diff))) {
                const team = await this.fetchTeam(teamId);
                this.teams.push(team);
            }
            this.specific_permissions = this.specific_permissions.concat([...diff]);
        },

        getTeamIdFromDiff(diff) {
            const teamId = diff.length ? diff[0].pivot.team_id : null;
            return this.teams.find(team => team.id === teamId) ? null : teamId;
        },

        async fetchTeam(id) {
            const { data: team } = await this.$axios.get(this.$laroute('admin.teams.show', { team: id }));
            return team.data;
        },

        hasSpecificPermission(permission) {
            return this.specific_permissions.some(perm => perm.id === permission.id && perm.pivot.team_id === permission.pivot.team_id);
        },

        updateTeams(permissions) {
            for (let permission of permissions) {
                if (permission.pivot.team_id) {
                    this.teamMap[permission.pivot.team_id];
                }
            }
        },

        specificPermissionsOf(team) {
            return this.specific_permissions.filter(perm => perm.pivot.team_id === team.id);
        },

        removePermission(permission) {
            const removable = this.specific_permissions.findIndex(
                perm => perm.id === permission.id && perm.pivot.team_id === permission.pivot.team_id
            );

            if (removable > -1) {
                this.specific_permissions = this.specific_permissions.filter((perm, index) => index !== removable);
            }
        },

        async saveSpecificPermissions() {
            this.form.permissions = this.specific_permissions;
            await this.form.put(this.$laroute('admin.users.update-permissions', { user: this.user.id }));

            this.$toasted.success('Permissões salvas com sucesso.');
        },
    },
};
</script>
