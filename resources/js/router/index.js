import { createRouter, createWebHistory } from "vue-router";



import loginForm from  '../components/login.vue'
import dashboard from '../components/dashboard.vue'

import itemsIndex from '../components/items/index.vue'
import itemsNew from '../components/items/new.vue'
import itemsEdit from '../components/items/edit.vue'

import departmentIndex from '../components/department/index.vue'
import departmentNew from '../components/department/new.vue'
import departmentEdit from '../components/department/edit.vue'

import companyIndex from '../components/company/index.vue'
import companyNew from '../components/company/new.vue'
import companyEdit from '../components/company/edit.vue'

import employeeIndex from '../components/employee/index.vue'
import employeeNew from '../components/employee/new.vue'
import employeeEdit from '../components/employee/edit.vue'

import unitIndex from '../components/unit/index.vue'
import unitNew from '../components/unit/new.vue'
import unitEdit from '../components/unit/edit.vue'

import vendorIndex from '../components/vendor/index.vue'
import vendorNew from '../components/vendor/new.vue'
import vendorEdit from '../components/vendor/edit.vue'

import projectIndex from '../components/project/index.vue'
import projectNew from '../components/project/new.vue'
import projectEdit from '../components/project/edit.vue'

import pur_reqIndex from '../components/pur_req/index.vue'
import pur_reqNew from '../components/pur_req/new.vue'

import pur_quoteIndex from '../components/pur_quote/index.vue'
import pur_quoteNew from '../components/pur_quote/new.vue'
import pur_quoteView from '../components/pur_quote/view.vue'

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

    {
        path:'/company',
        component: companyIndex,
    },

    {
        path:'/company/new',
        component: companyNew,
    },
    {
        path:'/company/edit',
        component: companyEdit,
    },

    {
        path:'/employee',
        component: employeeIndex,
    },

    {
        path:'/employee/new',
        component: employeeNew,
    },
    {
        path:'/employee/edit',
        component: employeeEdit,
    },

    {
        path:'/unit',
        component: unitIndex,
    },

    {
        path:'/unit/new',
        component: unitNew,
    },
    {
        path:'/unit/edit',
        component: unitEdit,
    },

    {
        path:'/vendor',
        component: vendorIndex,
    },

    {
        path:'/vendor/new',
        component: vendorNew,
    },
    {
        path:'/vendor/edit',
        component: vendorEdit,
    },

    {
        path:'/project',
        component: projectIndex,
    },

    {
        path:'/project/new',
        component: projectNew,
    },
    {
        path:'/project/edit',
        component: projectEdit,
    },


    {
        path:'/pur_req',
        component: pur_reqIndex,
    },
    {
        path:'/pur_req/new',
        component: pur_reqNew,
    },

    {
        path:'/pur_quote',
        component: pur_quoteIndex,
    },
    {
        path:'/pur_quote/new',
        component: pur_quoteNew,
    },
    {
        path:'/pur_quote/view',
        component: pur_quoteView,
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
