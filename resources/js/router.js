import Vue from 'vue';
import Router from 'vue-router';
import index from './components/index';
import logs from './components/logs';
import login from './components/login';
import perms from './components/Admin/permission';
import users from './components/Admin/users';



Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'index',
            component: index
        },
        {
            path: '/logs',
            name: 'logs',
            component: logs
        },
        {
            path: '/admUsers',
            name: 'users',
            component: users
        },
        {
            path: '/admPerms',
            name: 'perms',
            component: perms
        },
        {
            path: '/login',
            name: 'login',
            component: login
        },
    ]
})