require('./bootstrap');

import Vue from 'vue'
import vuetify from '../plugins/vuetify' // path to vuetify export

import router from './router.js'
import App from './App.vue'

import User from './helpers/User'
window.User = User

const app = new Vue({
    el: '#app',
    vuetify,
    components: { App },
    router,
})