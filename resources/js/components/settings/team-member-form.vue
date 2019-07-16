<template lang="pug">
    modal(ref="modal")
        span(slot="title") Membro
        input-text(v-model="form.name" field="name" :form="form" label="Nome")
        input-text(v-model="form.email" field="email" :form="form" label="E-mail")
        input-text(v-model="form.phone" field="phone" :form="form" label="Telefone" v-mask="['(##) ####-####', '(##) #####-####']")
        input-text(v-model="form.password" field="password" type="password" :form="form" label="Senha")
        input-text(v-model="form.password_confirmation" field="password_confirmation" type="password" :form="form" label="Confirmação de senha")

        b-form-group(label="Sexo")
            b-form-radio-group(v-model="form.gender" :options="[{text: 'Masculino', value: 'male', checked: true}, {text: 'Feminino', value: 'female'}]" name="sexo")

        div(slot="footer")
            button.btn.btn-default.mr-2(@click="cancel") Cancelar
            button-loading.btn.btn-primary(@click="save(form)" :loading="form.submitting") Salvar
</template>

<script>
    import ModalComponent from '@/mixins/modal-component';

    export default {
        mixins: [ModalComponent],

        data() {
            return {
                form: new Form(),
            };
        },

        methods: {
            setup(member = { gender: 'male' }) {
                this.form = new Form(member);
            },

            async save(form) {
                const { data: member } = await form.save(route('members.index'));
                this.$toasted.show('Membro salvo com sucesso');
                this.$emit('saved', member.data);
                this.close();
            },

            cancel() {
                this.form.reset();
                this.close();
            },
        },
    };
</script>
