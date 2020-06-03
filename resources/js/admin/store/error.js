const state = {
  code: null
}

const getters = {
  code: state => state.code,
}

const mutations = {
  setCode(state, code) {
    state.code = code
  }
}

const actions = {
  setCode(context, code) {
    context.commit('setcode', code);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
