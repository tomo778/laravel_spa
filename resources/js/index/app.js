import './bootstrap'
import Vue from 'vue'
import VueMeta from 'vue-meta'
Vue.use(VueMeta, {
    refreshOnceOnNavigation: true
});
import VModal from 'vue-js-modal'
Vue.use(VModal)
import router from './router'
import store from './store'
import App from './App.vue'
import * as filters from './filters';
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App />'
  })
}

createApp()