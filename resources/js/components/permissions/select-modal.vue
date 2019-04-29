<template lang="pug">
    modal(ref="modal")
        span(slot="title") Permissões

        input-select-2.ml-auto(
            label="Selecione o Escopo"
            v-model="team_id"
            :searchable="true"
            :async="false"
            search-placeholder="Pesquisar alas..."
            search-empty-message="Nenhuma ala encontrada."
            @click.stop="() => null",
            :options="teams",
            placeholder="Deixe vazio para global")

        .card.cursor-pointer.shadow-sm.mt-1(v-for="permission of available_permissions", :key="permission.id",
            @click="toggleSelection(permission)"
            :class="{ 'border-primary': isSelected(permission) }")
            .card-body.pl-3.pr-1.py-1.d-flex.align-items-center
                i.mr-2.fa.fa-fw.fa-check.check-icon(:class="{'visible text-primary': isSelected(permission)}")
                span {{ permission.label }}

        .mt-3(v-if="selectedObjects.length") {{ selectedObjects.length }} permissão(ões) selecionada(as)

        .d-flex.flex-wrap.align-items-center.w-100(slot="footer")
            button.btn.btn-light.mr-auto(@click="selectAll") Selecionar Todos
            button.btn.btn-light.ml-2(@click="close") Fechar
            button.btn.btn-primary.ml-2(@click="addPermissions", :disabled="!selectedPermissions.length") Adicionar
</template>

<style lang="sass" scoped>
.check-icon
    opacity: 0.1
    &.visible
        opacity: 1
</style>


<script>
import ModalComponent from '@/mixins/modal-component';
import HasSelectableObjects from '@/mixins/has-selectable-objects';

export default {
    mixins: [ModalComponent, HasSelectableObjects],

    props: {
        permissions: { default: () => [] },
        currentPermissions: { default: () => [] },
        teams: { default: () => [] },
    },

    data() {
        return {
            team_id: null,
        };
    },

    computed: {
        available_permissions() {
            const available = this.permissions.filter(p1 => !this.currentPermissions.some(p2 => p1.id === p2.id));
            available.forEach(per => this.$set(per, 'pivot', { team_id: null }));
            return available;
        },

        selectedPermissions() {
            return this.selectedObjects.map(permission => {
                this.$set(permission, 'pivot', { team_id: this.team_id });
                return { ...permission };
            });
        },
    },

    methods: {
        addPermissions() {
            this.$emit('selected', this.selectedPermissions);
            this.close();
        },

        selectAll() {
            this.selectedObjects = this.available_permissions;
        },

        afterClose() {
            this.clearSelection();
            this.team_id = null;
        },
    },
};
</script>
