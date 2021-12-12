require('./bootstrap');

/*
|----------------------------------------------------------------
| Vue 3
|----------------------------------------------------------------
*/

import {createApp, h} from 'vue';
import {createInertiaApp, Link} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';

InertiaProgress.init();

const el = document.getElementById('app');


createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup ({el, App, props, plugin}) {
    createApp({
      render: () => h(App, {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: name => import(`./Pages/${name}`).then(module => module.default),
      })
    })
      .mixin({
        methods: {
          route: (name, params, absolute) => route(name, params, absolute, Ziggy),
        },
      }).component('ilink', Link)
      .use(plugin)
      .mount(el);
  },
});





