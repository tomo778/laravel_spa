import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import like from './like'
import categorys from './categorys'
import loadingBar from './loadingBar'
import error from './error'
import message from './message'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    like,
    categorys,
    loadingBar,
    error,
    message
  }
})

export default store
