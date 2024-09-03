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
import vendorEdit from '../components/vendor/edit.vue'

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
import job_issuePrint from '../components/job_issue/print.vue'

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
        path:'/pur_req/view',
        component: pur_reqView,
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
    {
        path:'/pur_quote/print',
        component: pur_quotePrint,
    },

    {
        path:'/pur_aoq',
        component: pur_aoqIndex,
    },
    {
        path:'/pur_aoq/new',
        component: pur_aoqNew,
    },

    {
        path:'/pur_aoq/view',
        component: pur_aoqView,
    },

    {
        path:'/pur_aoq/print_te',
        component: pur_aoqPrintTe,
    },
    {
        path:'/pur_aoq/awarded',
        component: pur_aoqAwarded,
    },

    {
        path:'/pur_po',
        component: pur_poIndex,
    },
    {
        path:'/pur_po/new',
        component: pur_poNew,
    },
    {
        path:'/pur_po/view',
        component: pur_poView,
    },
    {
        path:'/pur_po/edit',
        component: pur_poEdit,
    },
    {
        path:'/pur_po/print',
        component: pur_poPrint,
    },

    {
        path:'/po_direct',
        component: pur_poDirect,
    },
    {
        path:'/po_repeat',
        component: pur_poRepeat,
    },
    {
        path:'/po_repeat/view',
        component: pur_poRepeatView,
    },
    {
        path:'/po_direct/view',
        component: pur_poDirectView,
    },
    {
        path:'/pur_dr',
        component: pur_drIndex,
    },
    {
        path:'/pur_dr/new',
        component: pur_drNew,
    },
    {
        path:'/pur_dr/view',
        component: pur_drView,
    },

    {
        path:'/pur_disburse',
        component: pur_disburseIndex,
    },
    {
        path:'/pur_disburse/new',
        component: pur_disburseNew,
    },
    {
        path:'/pur_disburse/view',
        component: pur_disburseView,
    },

    {
        path:'/job_req',
        component: job_reqIndex,
    },
    {
        path:'/job_req/new',
        component: job_reqNew,
    },
    {
        path:'/job_req/view',
        component: job_reqView,
    },

    {
        path:'/job_quote',
        component: job_quoteIndex,
    },
    {
        path:'/job_quote/new',
        component: job_quoteNew,
    },
    {
        path:'/job_quote/view',
        component: job_quoteView,
    },
    {
        path:'/job_quote/print',
        component: job_quotePrint,
    },

    {
        path:'/job_aoq',
        component: job_aoqIndex,
    },
    {
        path:'/job_aoq/new',
        component: job_aoqNew,
    },

    {
        path:'/job_aoq/view',
        component: job_aoqView,
    },

    {
        path:'/job_aoq/print_te',
        component: job_aoqPrintTe,
    },
    {
        path:'/job_aoq/awarded',
        component: job_aoqAwarded,
    },

    {
        path:'/job_issue',
        component: job_issueIndex,
    },
    {
        path:'/job_issue/new',
        component: job_issueNew,
    },
    {
        path:'/job_issue/view',
        component: job_issueView,
    },
    {
        path:'/job_issue/edit',
        component: job_issueEdit,
    },
    {
        path:'/job_issue/print',
        component: job_issuePrint,
    },

    {
        path:'/:pathMatch(.*)*',
        name:'notFound',
        component: notFound,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
