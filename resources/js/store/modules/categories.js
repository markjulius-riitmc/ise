import axios from 'axios';

const state = {
    categories: [],
    category: {}
};

const getters = {
    allCategories: state => state.categories
};

const actions = {
    async fetchCategories({ commit }) {
        const response = await axios.get('/api/categories');
        commit('setCategories', response.data.data);
    },
    async addCategory({commit}, category) {
        const response = await axios.post('/api/category', category);
        commit('newCategory', response.data.data);
    }
};

const mutations = {
    setCategories: (state, categories) => (state.categories = categories),
    newCategory: (state, category) => state.categories.unshift(category)
};

export default {
    state,
    getters,
    actions,
    mutations
};

// // State: Single source of truth
// state: {
//     categories: [],
//     category: {},
//     submitStatus: null,

// },
// // Computed properties
// getters: {
//     // categories (state) {
//     //     return state.categories
//     // },
// },
// // Methods
// actions: {
//     fetchCategories({state, commit}, data) {
//         axios.get(`/api/categories?page=${data}`)
//         .then(res => {
//             const categories = res.data 
//             commit('setCategories', categories)
//             return state.categories
//         })
//         .catch(err => console.log(err));
//     },
//     makePagination(meta, links) {
//         let pagination = {
//             current_page: meta.current_page,
//             total: this.makeAbsNumber(meta.total/meta.per_page),
//         }
//         this.pagination = pagination;
//     },
//     makeAbsNumber(num) {
//         let mod = (num % 1)
//         if (mod < 0.5 && num > 0) {
//             num -= mod
//             num += 1
//         } else {
//             num = Math.round(num)
//         }
//         return num
//     },
//     createCategory({commit}, category) {
//         axios.post('/api/category', category)
//         .then(res => {
//             console.log(res.data.data);
//         })
//         .catch(error => console.log(error))
//     },
//     updateCategory({commit}, id, category) {
//         axios.put(`/api/category/${id}`, category)
//         .then(res => {
//             console.log(res.data.data)
//         })
//         .catch(error => console.log(error))
//     }
// },
// // Simple functions to mutate a state
// mutations: {
//     setCategories(state, categories) {
//         state.categories = categories
//     },
// }