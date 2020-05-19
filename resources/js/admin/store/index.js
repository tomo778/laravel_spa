import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import categorys from './categorys'
import error from './error'
import message from './message'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    categorys,
    error,
    message
  }
})

export default store
