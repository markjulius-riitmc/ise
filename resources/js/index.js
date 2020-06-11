require('./bootstrap');

import Vue from 'vue'
import vuetify from '../plugins/vuetify' // path to vuetify export
import router from './router'
import store from './store'
import App from './App.vue'
import User from './helpers/User'

window.User = User
window.EventBus = new Vue()

// const app = new Vue({
//     el: '#app',
//     vuetify,
//     components: { App },
//     router,
//     store,
// })

new Vue({
    vuetify,
    store,
    router,
    render: h => h(App),
}).$mount('#app');