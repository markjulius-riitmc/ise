require('./bootstrap');

import Vue from 'vue'
import vuetify from './plugins/vuetify' // path to vuetify export

import Router from './router.js'
import App from './App.vue'

const app = new Vue({
    el: '#app',
    vuetify,
    components: { App },
    router: Router,
})