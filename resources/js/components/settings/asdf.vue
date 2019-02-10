<template lang="pug">
    div
        user-settings
</template>

<script>
import VueRouter from 'vue-router';
import TeamSettings from './team-settings';
import TeamSettingsMenu from './team-settings-menu';
import TeamServices from './team-services';
import TeamMembers from './team-members';
// import TeamInfo from './team-info';
import UserSettings from './user-settings';
import Clients from './team-clients';

// const router = new VueRouter({
//     routes: [
//         { path: '/', redirect: { name: 'info', params: { id: Globals.team && Globals.team.id } } },
//         { path: '/user', name: 'user-settings', component: UserSettings },
//         {
//             path: '/team/:id',
//             name: 'team',
//             components: {
//                 default: TeamSettings,
//                 subSettings: TeamSettingsMenu,
//             },
//             children: [
//                 { path: '/', redirect: 'info' },
//                 { path: 'info', name: 'info', component: TeamInfo },
//                 { path: 'services', name: 'services', component: TeamServices },
//                 { path: 'clients', name: 'clients', component: Clients },
//                 { path: 'members', name: 'members', component: TeamMembers },
//             ],
//         },
//     ],
//     linkActiveClass: 'active',
// });

export default {
    // router,
    components: {
        UserSettings,
    },

    computed: {
        currentTeamRoute() {
            const name = this.$route.path.includes('team') ? this.$route.name : 'info';
            return { name, params: { id: this.$team.id } };
        },
    },
};
</script>
