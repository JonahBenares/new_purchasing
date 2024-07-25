import { createRouter, createWebHistory } from "vue-router";



import loginForm from  '../components/login.vue'
import dashboard from '../components/dashboard.vue'

import itemsIndex from '../components/items/index.vue'
import itemsNew from '../components/items/new.vue'
import itemsEdit from '../components/items/edit.vue'

import departmentIndex from '../components/department/index.vue'
import departmentNew from '../components/department/new.vue'
import departmentEdit from '../components/department/edit.vue'


const routes = [
    {
        path:'/',
        name:'login',
        component: loginForm,
    },
    {
        path:'/dashboard',
        component: dashboard,
    },
    

    {
        path:'/items',
        component: itemsIndex,
    },

    {
        path:'/items/new',
        component: itemsNew,
    },
    {
        path:'/items/edit',
        component: itemsEdit,
    },

    {
        path:'/department',
        component: departmentIndex,
    },

    {
        path:'/department/new',
        component: departmentNew,
    },
    {
        path:'/department/edit',
        component: departmentEdit,
    },
    // {
    //     path:'/:pathMatch(.*)*',
    //     name:'notFound',
    //     component: notFound,
    // }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
