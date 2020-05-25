import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  news: null
}

const getters = {
  news: state => !! state.news
}

const mutations = {
  setNews (state, news) {
    state.news = news
  }
}

const actions = {
  likeChange(context, data) {
    if (!context.rootGetters["auth/check"]) {
      alert("いいね機能を使うにはログインしてください。");
      return false;
    }
    if (data.liked) {
      context.dispatch('unlike', data.id);
    } else {
      context.dispatch('like', data.id);
    }
  },
  async like(context, id) {
    const response = await axios.put(`/api/news/like/${id}`);
    if (response.status !== OK) {
      context.commit("error/setCode", response.status, { root: true });
      return false;
    }
    let news = state.news.map(news => {
      if (news.id == response.data.news_id) {
        news.likes_count += 1;
        news.liked_by_user = true;
      }
      return news;
    });
    context.commit('setNews', news)
  },
  async unlike(context, id) {
    const response = await axios.delete(`/api/news/like/${id}`);
    if (response.status !== OK) {
      context.commit("error/setCode", response.status, { root: true });
      return false;
    }
    let news = state.news.map(news => {
      if (news.id == response.data.news_id) {
        news.likes_count -= 1;
        news.liked_by_user = false;
      }
      return news;
    });
    context.commit('setNews', news)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
