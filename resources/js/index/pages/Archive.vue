<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 font-italic border-bottom">{{y}}年{{m}}月</h3>
    <Pagination :data="items"></Pagination>
    <NewsBlock v-for="news in news_arr" :key="news.id" :news="news" @like="onLikeClick" />
    <Pagination :data="items"></Pagination>
  </div>
</template>

<script>
import { OK,NOT_FOUND } from "../util";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";

export default {
  components: {
    NewsBlock,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: true,
      default: 1
    },
    y: {
      type: String,
      required: false
    },
    m: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      news_arr: [],
      items: [],
    };
  },
  created() {
    this.list();
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
      const response = await axios.get(`/api/archive?page=${this.page}&y=${this.y}&m=${this.m}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      if (response.data.data == "") {
        this.$store.commit("error/setCode", NOT_FOUND);
        return false;
      }
      this.news_arr = response.data.data;
      this.items = response.data;
    }
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