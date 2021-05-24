import Vue from 'vue'
import VueRouter from 'vue-router'

import Register from '../views/Auth/Register.vue'
import Login from '../views/Auth/Login.vue'
import Tasks from "../views/Tasks";

Vue.use(VueRouter);

const router = new VueRouter({
    routes:[
        {path: '/register', component: Register,meta: { guest: true }},
        {path: '/login', component: Login,meta: { guest: true }},
        {path: '/home', component: Tasks, meta: { requiresAuth: true}}
    ]
});

export default router
