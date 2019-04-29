<template lang="pug">
    .site-menubar(:class="{ active }")
        scrollable.site-menubar-body.flex-fill
            ul.site-menu(v-for="menu of appMenus", :key="menu.group")
                li.site-menu-header(v-if="menu.group") {{ menu.group }}
                li.site-menu-item(v-for="item of menu.items", :key="item.label", :class="item.active")
                    a(:href="item.url", @click="expand(item)")
                        i.icon-left(aria-hidden='true', :class="item.iconLeft")
                        | {{ item.label }}
                        i.fa.fa-fw.fa-angle-down.icon-right(v-if="item.children")
                    ul.site-menu-sub(v-if="item.children")
                        li.site-menu-item(v-for="child of item.children", :key="child.label", :active="{active: child.active}")
                            a(:href="child.url") {{ child.label }}

        .site-menubar-footer.d-flex.align-items-center.justify-content-center
            a.site-menubar-footer-button(href='#', v-tooltip="'Configurações'")
                i.fa.fa-tools
            a.site-menubar-footer-button(href='#', v-tooltip="'Ala'")
                i.fa.fa-users
            a.site-menubar-footer-button(href='#', v-tooltip="'Sair'")
                i.fa.fa-power-off

</template>

<script>
export default {
    props: {
        active: { default: false },
        menus: {
            default: () => [],
        },
    },

    data() {
        return {
            appMenus: [
                {
                    group: 'Painel',
                    items: [
                        {
                            label: 'Dashboard',
                            iconLeft: 'fa fa-fw fa-tachometer-alt',
                            active: 'active',
                            children: [
                                { label: 'Agenda', url: this.$laroute('agenda'), active: 'active' },
                                { label: 'Planejamento', url: '/' },
                            ],
                        },
                        {
                            label: 'Dashboard 2',
                            iconLeft: 'fa fa-fw fa-tachometer-alt',
                            active: '',
                            children: [{ label: 'Agenda 2', url: '/agenda-2' }],
                        },
                        {
                            label: 'Home',
                            iconLeft: 'fa fa-home',
                            active: '',
                            url: '/',
                        },
                    ],
                },
            ],
        };
    },

    methods: {
        expand(item) {
            if (!item.children) return;
            item.active = item.active ? null : 'active';
        },
    },
};
</script>
