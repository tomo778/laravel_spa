const state = {
  content: '',
  type:''
}

const mutations = {
  setContent (state, { content, timeout }) {
    state.content = content.mes
    state.type = content.type

    if (typeof timeout === 'undefined') {
      timeout = 2500
    }

    setTimeout(() => (state.content = ''), timeout)
  }
}

export default {
  namespaced: true,
  state,
  mutations
}
