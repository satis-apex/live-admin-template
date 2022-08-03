import './bootstrap';
import '../css/app.css';

//element ui 
import 'element-plus/theme-chalk/display.css'

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
//layout
import Admin from '@/Layouts/Admin.vue'
import Auth from '@/Layouts/Auth.vue'
import NavLink from '@/Layouts/NavLink.vue'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // resolve: async (name) => {
    //     resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
    // },
    resolve: async name => {
        const comps = import.meta.glob('./Pages/**/*.vue');
        const match = comps[`./Pages/${name}.vue`];
        if (match == undefined) {
            console.log('vue file not created');
            return import('./Errors/404page.vue');
        }
        const page = (await match()).default;

        if (page.layout === undefined) {
            page.layout = Admin
        }
        else if (page.layout == 'auth') {
            page.layout = Auth
        }
        else {
            page.layout = Admin
        }
        return page
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .component("Link", Link)
            .component("NavLink", NavLink)
            .mixin({ methods: { appRoute: window.route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
