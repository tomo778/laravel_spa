import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import categorys from './categorys'
import config from './config'
import loading from './loading'
import loadingBar from './loadingBar'
import flashMessage from './flashMessage'
import error from './error'
import message from './message'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    categorys,
    config,
    loading,
    loadingBar,
    flashMessage,
    error,
    message
  }
})

export default store
