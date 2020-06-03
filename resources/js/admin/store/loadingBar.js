const state = {
  processing: false
}

const getters = {
  processing: state => state.processing,
}

const mutations = {
  setProcessing(state, processing) {
    state.processing = processing
  }
}

const actions = {
  start(context, data) {
    context.commit('setProcessing', true);
  },
  stop(context, data) {
    context.commit('setProcessing', false);
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}