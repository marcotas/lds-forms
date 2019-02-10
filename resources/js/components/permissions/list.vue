<template lang="pug">
    div
        .mb-2
            b {{ titleName | capitalize }}
            span.text-muted(v-if="role && role.team")  em {{ role.team.name }}
        ul.list-unstyled.mb-0
            li.permission.d-flex.align-items-center(v-for="permission of innerPermissions", :key="permission.id")
                i.fa.fa-fw.fa-angle-right.mr-2.text-muted
                span {{ permission.label }}
                span.ml-3.remove-icon(@click="$emit('remove', permission)", v-if="removable") remover
            li(v-if="!innerPermissions.length")
                em.text-muted.opacity-50 Sem permissões
</template>

<style lang="sass" scoped>
@import '~styles/variables'

.permission
    &:hover
        .remove-icon
            opacity: 1

    .remove-icon
        color: gray
        opacity: 0
        cursor: pointer
        &:hover
            color: $danger
            opacity: 1
</style>


<script>
export default {
    props: {
        permissions: { default: null, type: Array },
        role: { default: null, type: Object },
        team: { default: null, type: Object },
        removable: { default: true },
    },

    computed: {
        innerPermissions() {
            return this.permissions || (this.role && this.role.specific_permissions) || [];
        },

        isGlobal() {
            return !this.team && !this.role;
        },

        titleName() {
            return this.isGlobal ? 'Escopo Global' : (this.team && this.team.name) || this.role.label;
        },
    },
};
</script>
