import './bootstrap'
import Vue from 'vue'
import VueMeta from 'vue-meta'
Vue.use(VueMeta, {
  // optional pluginOptions
  refreshOnceOnNavigation: true
})
import VModal from 'vue-js-modal'
Vue.use(VModal)
import VueLoading from 'vue-loading-template'
Vue.use(VueLoading, /** options **/)
import router from './router'
import store from './store'
import App from './App.vue'

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