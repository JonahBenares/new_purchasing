import { createRouter, createWebHistory } from "vue-router";


import loginForm from  '../components/login.vue'
import dashboard from '../components/dashboard.vue'


// import supplierIndex from '../components/supplier/index.vue'
// import supplierNew from '../components/supplier/new.vue'
// import supplierEdit from '../components/supplier/edit.vue'


import notFound from '../components/notFound.vue'

const routes = [
    {
        path:'/',
        name:'login',
        component: loginForm,
    },
    {
        path:'/dashboard',
        name: 'dashboard',
        component: dashboard,
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

// router.beforeEach((to,from) => {
//     if(to.meta.requiresAuth && !localStorage.getItem('token') ){
//         return { name: 'login'}
//     } 
// })



// router.beforeEach((to, from, next) => {
//     const requiresAuth = to.matched.some(x => x.meta.requiresAuth);
  
//     if (to.meta.requiresAuth && !localStorage.getItem('token')) {
//         return { name: 'login'}
//     } else if (to.meta.requiresAuth && localStorage.getItem('token')) {
//       next();
//     } else {
//       next();
//     }
//   });

export default router
