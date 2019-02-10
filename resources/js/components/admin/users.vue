<template lang="pug">
    .my-4
        router-view
</template>

<script>
import VueRouter from 'vue-router';
import UsersList from '@/components/users/index';
import AdminUserProfile from './users/profile';

const routes = [{ path: '/', name: 'users', component: UsersList }, { path: '/:id', name: 'profile', component: AdminUserProfile }];

const router = new VueRouter({ routes, linkActiveClass: 'active' });

export default {
    router,
};
</script>
