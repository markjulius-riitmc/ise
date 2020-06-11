import Vue from 'vue'
import Vuex from 'vuex'
import categories from './modules/categories';

// Load Vuex
Vue.use(Vuex)

// Create store
export default new Vuex.Store({
    modules: {
        categories
    }
})