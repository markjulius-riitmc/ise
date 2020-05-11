import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/Home'
import Page from './components/Page'
import Category from './views/Category'
import Equipment from './views/Equipment'
import User from './views/User'
import Site from './views/Site'
import Division from './views/Division'
import Department from './views/Department'
import Employee from './views/Employee'
import Supplier from './views/Supplier'
import ManageEquipment from './views/ManageEquipment'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        props: { title: "Home Component" }
    },
    {
        path: '/spa',
        name: 'page',
        component: Page,
        props: {
            title: "SPA Page Component",
            author : {
                name : "Fisayo Afolayan",
                role : "Software Engineer",
                code : "Always keep it clean"
            }
        }
    },
    {
        path: '/category',
        name: 'category',
        component: Category,
        props: { title: "Category View" }
    },
    {
        path: '/equipment',
        component: Equipment,
        props: { title: "Equipment View" }
    },
    {
        path: '/user',
        component: User,
        props: {title: "User view (ongoing)"}
    },
    {
        path: '/site',
        component: Site,
        props: { title: "Site view" }
    },
    {
        path: '/division',
        component: Division,
        props: {title: "Division View"}
    },
    {
        path: '/department',
        component: Department,
        props: {title: "Department View"},
    },
    {
        path: '/employee',
        component: Employee,
        props: {title: "Employee View"},
    },
    {
        path: '/supplier',
        component: Supplier,
        props: {title: "Supplier View"},
    },
    {
        path: '/manage-equipment',
        component: ManageEquipment,
        props: {title: "Manage Equipment View"}
    },
    
]

export default new VueRouter({
    routes,
    // hashbang: false,
    // mode: 'history'
})
