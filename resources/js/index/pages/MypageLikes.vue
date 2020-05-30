<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 border-bottom">お気に入り一覧</h3>
    <NewsBlock v-for="news in news_arr" :key="news.id" :news="news" @like="likeChange" />
    <Pagination :data="items"></Pagination>
  </div>
</template>

<script>
import { OK } from "../util";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";
import { mapState } from "vuex";

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
  page: {
    type: String,
    required: true,
    default: "1"
  },
  data() {
    return {
      items: []
    };
  },
  computed: mapState({
    news_arr: state => state.like.news
  }),
  watch: {
    $route: {
      async handler() {
        await this.init();
      },
      immediate: true
    }
  },
  created() {
    this.init();
  },
  methods: {
    async init() {
      this.$store.commit("loadingBar/start");
      const response = await axios.get(`/api/likes?page=${this.page}`);
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.$store.commit("like/setNews", response.data.data);
      this.items = response.data;
    },
    async likeChange(data) {
      await this.$store.dispatch("like/likeChange", data);
    }
  },
};
</script>