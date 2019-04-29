<template lang="pug">
    .nav-settings
        h5.nav-heading Configurações da Ala

        .d-flex
            input-select(v-model="selectedTeamId", :options="$teams")

        ul.nav.flex-column
            li.nav-item
                router-link.nav-link(:to="{name: 'info'}")
                    i.fa.fa-fw.fa-home.mr-2
                    span Informações
                router-link.nav-link(:to="{name: 'services'}", v-if="$can('manage.team.services', selectedTeamId)")
                    i.fa.fa-fw.fa-cut.mr-2
                    span Serviços
                router-link.nav-link(:to="{name: 'clients'}", v-if="$can('manage.clients', selectedTeamId)")
                    i.fa.fa-fw.fa-user-tag.mr-2
                    span Clientes
                router-link.nav-link(:to="{name: 'members'}", v-if="$can('manage.team.members', selectedTeamId)")
                    i.fa.fa-fw.fa-users.mr-2
                    span Membros
</template>

<script>
export default {
    data() {
        return {
            selectedTeamId: this.$route.params.id || (this.$team && this.$team.id),
        };
    },

    watch: {
        selectedTeamId() {
            this.$router.push({ name: 'info', params: { id: this.selectedTeamId } });
        },
    },
};
</script>
