<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 border-bottom">お気に入り一覧</h3>
    <NewsBlock v-for="news in news_arr" :key="news.id" :news="news" @like="onLikeClick" />
    <Pagination :data="items"></Pagination>
  </div>
</template>

<script>
import { OK } from "../util";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";

export default {
  metaInfo() {
    return {
      title: "mypage",
      meta: [
        {
          name: "description",
          content: "newのサイト"
        }
      ]
    };
  },
  components: {
    Pagination,
    NewsBlock
  },
  props: {
    page: {
      type: Number,
      required: true,
      default: 1
    }
  },
  data() {
    return {
      news_arr: [],
      items: []
    };
  },
  methods: {
    onLikeClick({ id, liked }) {
      if (!this.$store.getters["auth/check"]) {
        alert("いいね機能を使うにはログインしてください。");
        return false;
      }
      if (liked) {
        this.unlike(id);
      } else {
        this.like(id);
      }
    },
    async like(id) {
      const response = await axios.put(`/api/news/like/${id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.news_arr = this.news_arr.map(news => {
        if (news.id == response.data.news_id) {
          news.likes_count += 1;
          news.liked_by_user = true;
        }
        return news;
      });
    },
    async unlike(id) {
      const response = await axios.delete(`/api/news/like/${id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.news_arr = this.news_arr.map(news => {
        if (news.id == response.data.news_id) {
          news.likes_count -= 1;
          news.liked_by_user = false;
        }
        return news;
      });
    },
    async list() {
      const response = await axios.get(`/api/likes?page=${this.page}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.news_arr = response.data.data;
      this.items = response.data;
    }
  },
  created() {
    this.list();
  },
  watch: {
    $route: {
      async handler() {
        await this.list();
      },
      immediate: true
    }
  }
};
</script>