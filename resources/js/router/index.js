import { createRouter, createWebHistory } from 'vue-router'
import auth from '@/services/auth'
import Login from '@/pages/auth/Login.vue'
import Register from '@/pages/auth/Register.vue'
import UsersIndex from '@/pages/users/UsersIndex.vue'
import UserShow from '@/pages/users/UserShow.vue'
import Dashboard from '@/pages/dashboard/Dashboard.vue'
import WebsitesIndex from '@/pages/websites/WebsitesIndex.vue'
import WebsitesCreate from '@/pages/websites/WebsitesCreate.vue'
import WebsitesShow from '@/pages/websites/WebsitesShow.vue'
import WebsitesEdit from '@/pages/websites/WebsitesEdit.vue'

const routes = [
    {
        path: '/',
        redirect: '/websites'
    },
    {
        path: '/admin',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true, roles: ['dashboard'] }
    },
    {
        path: '/websites',
        name: 'websites.index',
        component: WebsitesIndex,
        meta: { requiresAuth: true }
    },
    {
        path: '/websites/create',
        name: 'websites.create',
        component: WebsitesCreate,
        meta: { requiresAuth: true }
    },
    {
        path: '/websites/:id/edit',
        name: 'websites.edit',
        component: WebsitesEdit,
        meta: { requiresAuth: true }
    },
    {
        path: '/websites/:id',
        name: 'websites.show',
        component: WebsitesShow,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guest: true }
    },
    {
        path: '/users',
        name: 'users.index',
        component: UsersIndex,
        meta: { requiresAuth: true, roles: ['admin'] }
    },
    {
        path: '/users/:id',
        name: 'users.show',
        component: UserShow,
        meta: { requiresAuth: true, roles: ['admin'] }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guard for authentication and authorization
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')
    const userRole = auth.getUserRole()

    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (!token) {
            next({ name: 'login' })
        } else {
            next()
        }
    } else if (to.matched.some((record) => record.meta.guest)) {
        if (token) {
            next({ name: 'admin' })
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router
