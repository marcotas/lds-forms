<template lang="pug">
    .card.border-0.shadow-sm
        .card-body
            h5.card-title.mb-0.d-flex.align-items-center
                | Membros
                span(v-if="members.meta") ({{ members.meta.total }})
                button.ml-3.btn.btn-success.btn-sm(@click="$refs.memberForm.open().setup()")
                    i.fa.fa-plus.mr-2
                    | Novo Membro
                input-text.mb-0.w-25.ml-auto(v-model="search" placeholder="Pesquisar" right-icon="fa fa-search")
        .table-responsive
            table.table.mb-0
                thead.bg-grey
                    th
                    th Nome
                    th Email
                    th Sexo
                    th
                tbody
                    tr(v-for="member of members.data", :key="member.id")
                        td.align-middle
                            avatar(:username="member.name", :src="member.photo_url", :size="35")
                        td.align-middle
                            span {{ member.name }}
                            span(v-if="$user.id === member.id")  (Você)
                        td.align-middle {{ member.email }}
                        td.align-middle {{ member.gender | gender | capitalize }}
                        td.align-middle
                            .btn-group
                                button.btn.btn-sm.btn-default(@click="edit(member)")
                                    i.fa.fa-fw.fa-edit
                                button-loading.btn.btn-sm.btn-danger(
                                    v-if="member.id !== $user.id"
                                    :loading="isLoading(member)"
                                    @click="confirmRemove(member)")
                                    i.fa.fa-fw.fa-sign-out-alt(v-if="!isLoading(member)")
        .card-body
            pagination(:meta="members.meta" :links="members.links" @page="fetchMembers")
        member-form(ref="memberForm" @saved="fetchMembers()")
</template>

<script>
    import _ from 'lodash';
    import MemberForm from './team-member-form';

    export default {
        components: { MemberForm },

        props: {
            team: Object,
        },

        data() {
            return {
                search: null,
                pagination: { page: 1, perPage: 15 },
                loadingMembers: [],
                members: {
                    data: [],
                    links: null,
                    meta: null,
                },
            };
        },

        created() {
            this.fetchMembers();
        },

        watch: {
            search: _.debounce(function(search) {
                this.pagination.page = 1;
                this.fetchMembers();
            }, 500),
        },

        methods: {
            async fetchMembers({ page, perPage } = {}) {
                this.pagination.page = page || this.pagination.page;
                this.pagination.perPage = perPage || this.pagination.perPage;
                const params = { ...this.pagination, search: this.search };
                const { data: members } = await this.$axios.get(route('members.index'), { params });
                this.members = members;
            },

            edit(member) {
                this.$refs.memberForm.open().setup(member);
            },

            async confirmRemove(member) {
                const confirmed = await this.$confirm({
                    title: 'Tem certeza?',
                    text: `Você está prestes a remover <strong>${member.name}</strong> de sua ala. Isso não poderá ser desfeito.`,
                });

                if (!confirmed) return;

                return this.remove(member);
            },

            async remove(member) {
                member = new Form(member);
                this.startLoading(member);
                const response = await member.delete(route('members.destroy', member));
                this.stopLoading(member);
                this.fetchMembers();
            },

            startLoading(member) {
                if (this.isLoading(member)) return;
                this.loadingMembers.push(member.id);
            },

            stopLoading(member) {
                if (!this.isLoading(member)) return;
                this.loadingMembers.splice(this.loadingMembers.indexOf(member.id), 1);
            },

            isLoading(member) {
                return this.loadingMembers.includes(member.id);
            },
        },
    };
</script>
