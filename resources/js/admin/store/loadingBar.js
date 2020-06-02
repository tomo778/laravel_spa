const state = {
  processing: false
}

const getters = {
  processing: state => state.processing,
}

const mutations = {
  start(state, processing) {
    state.processing = true;
  },
  stop(state, processing) {
    state.processing = false;
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations
}