import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  news: {},
  detail: {}
}

const getters = {
  news: state => state.news,
  detail: state => state.detail
}

const mutations = {
  setNews(state, news) {
    state.news = news
  },
  setDetail(state, detail) {
    state.detail = detail
  },
}

const actions = {
  likeChange(context, data) {
    if (!context.rootGetters["auth/check"]) {
      alert("いいね機能を使うにはログインしてください。");
      return false;
    }
    if (data.liked) {
      context.dispatch('unlike', data);
    } else {
      context.dispatch('like', data);
    }
  },
  async like(context, data) {
    const response = await axios.put(`/api/news/like/${data.id}`);
    if (response.status !== OK) {
      context.commit("error/setCode", response.status, { root: true });
      return false;
    }
    if (data.detail) {
      let detail = state.detail;
      detail.likes_count += 1;
      detail.liked_by_user = true;
      context.commit('setDetail', detail)
    } else {
      let news = state.news.map(news => {
        if (news.id == response.data.news_id) {
          news.likes_count += 1;
          news.liked_by_user = true;
        }
        return news;
      });
      context.commit('setNews', news);
    }
  },
  async unlike(context, data) {
    const response = await axios.delete(`/api/news/like/${data.id}`);
    if (response.status !== OK) {
      context.commit("error/setCode", response.status, { root: true });
      return false;
    }
    if (data.detail) {
      let detail = state.detail;
      detail.likes_count -= 1;
      detail.liked_by_user = false;
      context.commit('setDetail', detail)
    } else {
      let news = state.news.map(news => {
        if (news.id == response.data.news_id) {
          news.likes_count -= 1;
          news.liked_by_user = false;
        }
        return news;
      });
      context.commit('setNews', news);
    }
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
