import './bootstrap';
import '../css/app.css';
import 'simplebar/dist/simplebar.min.css';
//element ui 
import 'element-plus/theme-chalk/display.css'
/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'
/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
/* import all icons */
import { fas } from '@fortawesome/free-solid-svg-icons'
// import { fab } from '@fortawesome/free-brands-svg-icons'
// import { far } from '@fortawesome/free-regular-svg-icons'
/* add icons to the library */
library.add(fas);

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
            return import('./Errors/404page.vue');
        }
        const page = (await match()).default;

        if (page.layout === 'admin') {
            page.layout = Admin
        }
        else if (page.layout === 'auth') {
            page.layout = Auth
        }
        else {
            // page.layout = Admin
        }
        return page
    },
    setup({ el, app, props, plugin }) {
        const App = createApp({ render: () => h(app, props) })
        App.use(plugin)
        App.component("Link", Link)
        App.component("NavLink", NavLink)
        App.component('fa', FontAwesomeIcon)
        App.mixin({ methods: { appRoute: window.route } })
        App.mount(el);
    },
});

InertiaProgress.init({
    color: '#FF8B00'
});
