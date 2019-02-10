<template lang="pug">
    .my-4
        personal-info-form(:user="$user")
        password-form(:user="$user")
</template>

<script>
import PersonalInfoForm from '@/components/users/settings/personal-info-form';
import PasswordForm from '@/components/users/settings/password-form';

export default {
    components: { PersonalInfoForm, PasswordForm },
};
</script>
