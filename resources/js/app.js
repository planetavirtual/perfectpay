/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

import SalesIndex  from './components/sales/SalesIndex.vue';
import SalesCreate from './components/sales/SalesCreate.vue';
import SalesEdit   from './components/sales/SalesEdit.vue';

const routes = [
    {path: '/', components: { salesIndex: SalesIndex }},
    {path: '/sales/create', component: SalesCreate, name: 'createSale'},
    {path: '/sales/edit/:id', component: SalesEdit, name: 'editSale'},
]

const router = new VueRouter({ routes })

const app = new Vue({ router }).$mount('#app')
