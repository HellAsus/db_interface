

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import Vuex from 'vuex';
import Vuetify from 'vuetify'
import router from './router';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);
Vue.use(Vuetify);
Vue.use(Vuex);
Vue.config.productionTip = false

Vue.component('toolbar', require('./components/toolbar.vue').default);
Vue.component('point', require('./components/point.vue').default);

const token = localStorage.getItem('user-token')
if (token) { axios.defaults.headers.common['Authorization'] = 'Bearer ' + token }
axios.defaults.validateStatus = () => { return status < 500; };

export const store = new Vuex.Store({
    state: {
        api_key: null,
        perms: null,
        roles: null
    },
    getters: {
        GET_KEY: state => {
            return state.api_key;
        },
        GET_ROLES: state => {
            return state.roles;
        },
    },
    mutations: {
        SET_KEY: (state, payload) => {
            state.api_key = payload;
        },
        SET_ROLES: (state, payload) => {
            state.roles = payload;
        },
    },
    actions: {
        LOGIN: (context, payload) => {
            return new Promise((ok, fail) => {
                axios.post('/api/auth/login', { login: payload.login, password: payload.password }).then(responce => {
                    if (responce.status == 200) {
                        localStorage.setItem('user-token', responce.data.access_token);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + responce.data.access_token;
                        ok(true)
                    }
                    else { fail(false) }
                });
            })

        },
        LOGOUT: async (context) => {
            await axios.post('/api/auth/logout').then(responce => {
                context.commit('SET_KEY', null);
                context.commit('SET_ROLES', null);
                localStorage.removeItem("user-token");
                delete axios.defaults.headers.common['Authorization'];
            });

        },
        CHECK: async (context) => {
            const token = localStorage.getItem('user-token');
            if (token) {
                await axios.post('/api/auth/me').then(responce => {
                    if (responce.status == 200) {
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                        context.commit('SET_KEY', token);
                        context.commit('SET_ROLES', responce.data.data.role);
                    }
                    if (responce.status == 401) {
                        context.commit('SET_KEY', null);
                        context.commit('SET_ROLES', null);
                        localStorage.removeItem("user-token");
                        delete axios.defaults.headers.common['Authorization'];
                    } 
                });

            };
        },
    },
});


const app = new Vue({
    el: '#app',
    store,
    router
});
