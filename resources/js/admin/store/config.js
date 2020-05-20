import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  config: [],
}

const getters = {
  config: state => state.config,
}

const mutations = {
  setConfig(state, config) {
    state.config = config
  }
}

const actions = {
  async config(context, data) {
    const response = await axios.post(`/api/get/config`);
    if (response.status !== OK) {
      this.$store.commit("error/setCode", response.status);
      return false;
    }
    context.commit('setConfig', response.data);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
