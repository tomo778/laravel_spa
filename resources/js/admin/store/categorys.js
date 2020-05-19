import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  categorys: [],
}

const getters = {
  categorys: state => state.categorys,
}

const mutations = {
  setCategorys(state, categorys) {
    state.categorys = categorys
  }
}

const actions = {
  async categorys(context, data) {
    const response = await axios.post(`/api/get/categorys`);
    if (response.status !== OK) {
      this.$store.commit("error/setCode", response.status);
      return false;
    }
    context.commit('setCategorys', response.data);
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
