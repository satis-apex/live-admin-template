import 'simplebar/dist/simplebar.min.css';
import '../sass/app.scss';
import '../css/app.css';


import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
//layout
import NavLink from '@/Layouts/NavLink.vue'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async name => {
        let parts = name.split('::')
        let type = false
        let match
        let page
        if (parts.length > 1) type = parts[0]
        if (type) {
            let directoryParts = parts[1].split('/');
            let fileName = directoryParts[directoryParts.length - 1]
            directoryParts.pop();
            try {
                if (directoryParts.length === 0) {
                    page = await import(`../../Modules/${type}/Resources/assets/js/Pages/${fileName}.vue`).then(module => module.default)
                }
                if (directoryParts.length === 1) {
                    page = await import(`../../Modules/${type}/Resources/assets/js/Pages/${directoryParts[0]}/${fileName}.vue`).then(module => module.default)
                }
                else if (directoryParts.length === 2) {
                    page = await import(`../../Modules/${type}/Resources/assets/js/Pages/${directoryParts[0]}/${directoryParts[1]}/${fileName}.vue`).then(module => module.default)
                }

            }
            catch (e) {
                console.log(e);
                return import('./Errors/404page.vue');
            }
        }
        else {
            const comps = import.meta.glob('./Pages/**/*.vue');
            match = comps[`./Pages/${name}.vue`];
            if (match == undefined) {
                return import('./Errors/404page.vue');
            }
            page = (await match()).default;
        }


        return page
    },
    setup({ el, app, props, plugin }) {
        const App = createApp({ render: () => h(app, props) })
        App.use(plugin)
        App.component("Link", Link)
        App.component("NavLink", NavLink)
        App.mixin({ methods: { appRoute: window.route } })
        App.mount(el);
    },
});

InertiaProgress.init({
    color: 'var(--complementary-color)'
});
