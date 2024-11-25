import { createRouter, createWebHistory } from "vue-router";


import notFound from '../components/notFound.vue'
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
import vendorNew2 from '../components/vendor/new2.vue'
import vendorEdit from '../components/vendor/edit.vue'
import vendorEdit2 from '../components/vendor/edit2.vue'

import projectIndex from '../components/project/index.vue'
import projectNew from '../components/project/new.vue'
import projectEdit from '../components/project/edit.vue'

import pur_reqIndex from '../components/pur_req/index.vue'
import pur_reqNew from '../components/pur_req/new.vue'
import pur_reqView from '../components/pur_req/view.vue'

import pur_quoteIndex from '../components/pur_quote/index.vue'
import pur_quoteNew from '../components/pur_quote/new.vue'
import pur_quoteView from '../components/pur_quote/view.vue'
import pur_quotePrint from '../components/pur_quote/print.vue'

import pur_aoqIndex from '../components/pur_aoq/index.vue'
import pur_aoqNew from '../components/pur_aoq/new.vue'
import pur_aoqView from '../components/pur_aoq/view.vue'
import pur_aoqPrintTe from '../components/pur_aoq/print_te.vue'
import pur_aoqAwarded from '../components/pur_aoq/awarded.vue'

import pur_poIndex from '../components/pur_po/index.vue'
import pur_poNew from '../components/pur_po/new.vue'
import pur_poEdit from '../components/pur_po/edit.vue'
import pur_poView from '../components/pur_po/view.vue'
import pur_poViewRevised from '../components/pur_po/view_revised.vue'
import pur_poPrint from '../components/pur_po/print.vue'

import pur_poDirect from '../components/pur_direct/index.vue'
import pur_poRepeat from '../components/pur_repeat/index.vue'
import pur_poRepeatView from '../components/pur_repeat/view.vue'
import pur_poDirectView from '../components/pur_direct/view.vue'

import pur_drIndex from '../components/pur_dr/index.vue'
import pur_drNew from '../components/pur_dr/new.vue'
import pur_drView from '../components/pur_dr/view.vue'

import pur_disburseIndex from '../components/pur_disburse/index.vue'
import pur_disburseNew from '../components/pur_disburse/new.vue'
import pur_disburseView from '../components/pur_disburse/view.vue'

import job_reqIndex from '../components/job_req/index.vue'
import job_reqNew from '../components/job_req/new.vue'
import job_reqView from '../components/job_req/view.vue'

import job_quoteIndex from '../components/job_quote/index.vue'
import job_quoteNew from '../components/job_quote/new.vue'
import job_quoteView from '../components/job_quote/view.vue'
import job_quotePrint from '../components/job_quote/print.vue'

import job_aoqIndex from '../components/job_aoq/index.vue'
import job_aoqNew from '../components/job_aoq/new.vue'
import job_aoqView from '../components/job_aoq/view.vue'
import job_aoqPrintTe from '../components/job_aoq/print_te.vue'
import job_aoqAwarded from '../components/job_aoq/awarded.vue'


import job_issueIndex from '../components/job_issue/index.vue'
import job_issueNew from '../components/job_issue/new.vue'
import job_issueEdit from '../components/job_issue/edit.vue'
import job_issueView from '../components/job_issue/view.vue'
import job_issueViewRevised from '../components/job_issue/view_revised.vue'
import job_issuePrint from '../components/job_issue/print.vue'

import job_issueDirect from '../components/job_direct/index.vue'
import job_issueDirectView from '../components/job_direct/view.vue'

import job_drIndex from '../components/job_dr/index.vue'
import job_drNew from '../components/job_dr/new.vue'
import job_drView from '../components/job_dr/view.vue'

import job_disburseIndex from '../components/job_disburse/index.vue'
import job_disburseNew from '../components/job_disburse/new.vue'
import job_disburseNew2 from '../components/job_disburse/new_2.vue'
import job_disburseView from '../components/job_disburse/view.vue'
import job_disburseView2 from '../components/job_disburse/view_2.vue'

import pur_weekly_repNew from '../components/pur_weekly_rep/new.vue'
import pur_weekly_repIndex from '../components/pur_weekly_rep/index.vue'
import pur_monthly_repNew from '../components/pur_monthly_rep/new.vue'
import pur_monthly_repIndex from '../components/pur_monthly_rep/index.vue'

import job_weekly_repNew from '../components/job_weekly_rep/new.vue'
import job_weekly_repIndex from '../components/job_weekly_rep/index.vue'
import job_monthly_repNew from '../components/job_monthly_rep/new.vue'
import job_monthly_repIndex from '../components/job_monthly_rep/index.vue'

import change_password from '../components/change_password/index.vue'

const routes = [
    {
        path:'/',
        name:'login',
        component: loginForm,
        meta:{
            requiresAuth:false
        }
    },
    {
        path:'/dashboard',
        component: dashboard,
        meta:{
            requiresAuth:true
        }
    },
    

    {
        path:'/items',
        component: itemsIndex,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/items/new',
        component: itemsNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/items/edit',
        component: itemsEdit,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/department',
        component: departmentIndex,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/department/new',
        component: departmentNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/department/edit',
        component: departmentEdit,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/company',
        component: companyIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/company/new',
        component: companyNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/company/edit/:id',
        component: companyEdit,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/employee',
        component: employeeIndex,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/employee/new',
        component: employeeNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/employee/edit/:id',
        component: employeeEdit,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    // {
    //     path:'/unit',
    //     component: unitIndex,
    // },

    // {
    //     path:'/unit/new',
    //     component: unitNew,
    // },
    // {
    //     path:'/unit/edit',
    //     component: unitEdit,
    // },

    {
        path:'/vendor',
        component: vendorIndex,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/vendor/new',
        component: vendorNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/vendor/edit/:id',
        component: vendorEdit,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/vendor/new2',
        component: vendorNew2,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/vendor/edit2/:id',
        component: vendorEdit2,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    // {
    //     path:'/project',
    //     component: projectIndex,
    // },

    // {
    //     path:'/project/new',
    //     component: projectNew,
    // },
    // {
    //     path:'/project/edit',
    //     component: projectEdit,
    // },


    {
        path:'/pur_req',
        component: pur_reqIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_req/new/:id',
        component: pur_reqNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_req/view/:id',
        component: pur_reqView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/pur_quote',
        component: pur_quoteIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_quote/new/:id',
        component: pur_quoteNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_quote/view/:id/:aoq_id',
        component: pur_quoteView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_quote/print/:id',
        component: pur_quotePrint,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/pur_aoq',
        component: pur_aoqIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_aoq/new',
        component: pur_aoqNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_aoq/view/:id/:aoq_details_id',
        component: pur_aoqView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_aoq/print_te/:id',
        component: pur_aoqPrintTe,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_aoq/awarded',
        component: pur_aoqAwarded,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/pur_po',
        component: pur_poIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_po/new/:id',
        component: pur_poNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_po/view/:id',
        component: pur_poView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_po/view_revised/:id',
        component: pur_poViewRevised,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_po/edit/:id',
        component: pur_poEdit,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_po/print',
        component: pur_poPrint,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/po_direct',
        component: pur_poDirect,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/po_repeat',
        component: pur_poRepeat,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/po_repeat/view',
        component: pur_poRepeatView,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/po_direct/view',
        component: pur_poDirectView,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_dr',
        component: pur_drIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_dr/new/:id',
        component: pur_drNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_dr/view/:id',
        component: pur_drView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/pur_disburse',
        component: pur_disburseIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_disburse/new',
        component: pur_disburseNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_disburse/view',
        component: pur_disburseView,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/job_req',
        component: job_reqIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_req/new/:id',
        component: job_reqNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_req/view/:id',
        component: job_reqView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/job_quote',
        component: job_quoteIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_quote/new/:id',
        component: job_quoteNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_quote/view/:id/:aoq_id',
        component: job_quoteView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_quote/print/:id',
        component: job_quotePrint,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_aoq',
        component: job_aoqIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_aoq/new',
        component: job_aoqNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_aoq/view/:id/:jo_aoq_details_id',
        component: job_aoqView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/job_aoq/print_te/:id',
        component: job_aoqPrintTe,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_aoq/awarded',
        component: job_aoqAwarded,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/job_issue',
        component: job_issueIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_issue/new/:id',
        component: job_issueNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_issue/view/:id',
        component: job_issueView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_issue/view_revised/:id',
        component: job_issueViewRevised,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_issue/edit/:id',
        component: job_issueEdit,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_issue/print',
        component: job_issuePrint,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/job_direct',
        component: job_issueDirect,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_direct/view',
        component: job_issueDirectView,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_dr',
        component: job_drIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_dr/new/:id',
        component: job_drNew,
        props:true,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_dr/view/:id',
        component: job_drView,
        props:true,
        meta:{
            requiresAuth:true
        }
    },

    {
        path:'/job_disburse',
        component: job_disburseIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_disburse/new',
        component: job_disburseNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_disburse/new2',
        component: job_disburseNew2,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_disburse/view',
        component: job_disburseView,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_disburse/view2',
        component: job_disburseView2,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_weekly_report',
        component: pur_weekly_repIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_weekly_report/new',
        component: pur_weekly_repNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_weekly_report',
        component: job_weekly_repIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_weekly_report/new',
        component: job_weekly_repNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/change_password',
        component: change_password,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_monthly_report',
        component: pur_monthly_repIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/pur_monthly_report/new',
        component: pur_monthly_repNew,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_monthly_report',
        component: job_monthly_repIndex,
        meta:{
            requiresAuth:true
        }
    },
    {
        path:'/job_monthly_report/new',
        component: job_monthly_repNew,
        meta:{
            requiresAuth:true
        }
    },
    
    {
        path:'/:pathMatch(.*)*',
        name:'notFound',
        component: notFound,
        meta:{
            requiresAuth:false
        }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to,from) => {
    if(to.meta.requiresAuth && !localStorage.getItem('token') ){
        return { name: 'login'}
    } 
})

export default router
