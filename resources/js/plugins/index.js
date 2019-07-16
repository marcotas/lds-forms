import Vue from 'vue';
import axios from 'axios';
import Toasted from 'vue-toasted';
import moment from 'moment-timezone';
import { loadProgressBar } from 'axios-progress-bar';
import VCalendar from 'v-calendar';
import VueAvatar from 'vue-avatar';
import BootstrapVue from 'bootstrap-vue';
import vBTooltip from 'bootstrap-vue';
import DevsquadUi from '@elitedevsquad/ui';
import VueRouter from 'vue-router';
import VueTheMask from 'vue-the-mask';
import VuePerfectScrollbar from 'vue-perfect-scrollbar';
import 'v-calendar/lib/v-calendar.min.css';
import Form from './form';
import VueSweetalert2 from 'vue-sweetalert2';
import VueClipboard from 'vue-clipboard2';

Vue.use(VueClipboard);
Vue.use(BootstrapVue);

Vue.directive('tooltip', vBTooltip);
Vue.use(DevsquadUi);
Vue.use(VueRouter);
Vue.use(VueTheMask);

window.moment = moment;
moment.locale('pt-br');
window.Form = Form;
window.$primaryColor = '#0091bc';
window.$dangerColor = '#ce2d4f';
export const laroute = route;

const options = {
    confirmButtonColor: $primaryColor,
};
Vue.use(VueSweetalert2, options);

Vue.prototype.$primaryColor = $primaryColor;
Vue.prototype.$dangerColor = $dangerColor;
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

Vue.mixin({
    methods: {
        route: window.route,
        async $confirm({ title, text } = {}) {
            title = title || (title !== false ? 'Tem ceteza que deseja realizar esta operação?' : null);
            const html = text;

            const response = await this.$swal({
                title,
                html,
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                cancelButtonColor: $dangerColor,
                reverseButtons: true,
            });

            return response.value || false;
        },
    },
});
Vue.use(VCalendar, { locale: 'pt_BR', firstDayOfWeek: 1 });
Vue.component('avatar', VueAvatar);
Vue.component('scrollable', VuePerfectScrollbar);
