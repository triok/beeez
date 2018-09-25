require('./bootstrap');
require('./bootstrap-notify');
require('./bootstrap-jquery');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

const Jobs = require('./components/Jobs.vue');
const Messanger = require('./components/messanger');

const routes = [
    {path: '/', component: Jobs},
    {path: '/jobs/category/:id', component: Jobs},
    {path: '/messages', component: Messanger},
    {path: '/messages/:id', component: Messanger}
];

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#app',
    router
});
