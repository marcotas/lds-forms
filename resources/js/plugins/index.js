import Vue from 'vue';
import Laroute from './laroute';
import axios from 'axios';
import Toasted from 'vue-toasted';
import moment from 'moment-timezone';
import { loadProgressBar } from 'axios-progress-bar';
import VCalendar from 'v-calendar';
import VueAvatar from 'vue-avatar';
import { Tabs, Card } from 'bootstrap-vue/es/components';
import vBTooltip from 'bootstrap-vue/es/directives/tooltip/tooltip';
import DevsquadUi, { Form } from '@elitedevsquad/ui';
import VueRouter from 'vue-router';
import VueTheMask from 'vue-the-mask';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';
import 'v-calendar/lib/v-calendar.min.css';

Vue.directive('tooltip', vBTooltip);
Vue.use(Tabs);
Vue.use(Card);
Vue.use(DevsquadUi);
Vue.use(VueRouter);
Vue.use(VueTheMask);

window.moment = moment;
window.Form = Form;
export const laroute = Laroute.route;

Vue.prototype.$user = Globals.user;
Vue.prototype.$teams = Globals.teams;
Vue.prototype.$team = Globals.team;
Vue.prototype.$laroute = laroute;
Vue.prototype.$axios = axios;
Vue.prototype.$obj_get = (obj, str) => {
    return str.split('.').reduce((a, c) => (a ? a[c] : null), obj);
};
Vue.prototype.$can = (permissionName, team = null) => {
    const user = Globals.user;
    if (!user) return false;
    team = 'object' === typeof team ? +team.id : +team;

    return (
        user.permissions.some(perm => perm.name === '*' && perm.pivot.team_id === null) ||
        user.permissions.some(perm => perm.name === permissionName && (perm.pivot.team_id === team || perm.pivot.team_id === null))
    );
};

loadProgressBar();

Vue.use(Toasted, {
    iconPack: 'fontawesome',
    duration: 5000,
});

Vue.use(VCalendar, { locale: 'pt_BR', firstDayOfWeek: 1 });
Vue.component('avatar', VueAvatar);
Vue.component('scrollable', VuePerfectScrollbar);
