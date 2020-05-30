<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 font-italic border-bottom">{{y}}年{{m}}月</h3>
    <Pagination :data="items"></Pagination>
    <NewsBlock v-for="news in news_arr" :key="news.id" :news="news" @like="likeChange"/>
    <Pagination :data="items"></Pagination>
  </div>
</template>

<script>
import { OK,NOT_FOUND } from "../util";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";
import { mapState } from "vuex";

export default {
  components: {
    NewsBlock,
    Pagination
  },
  props: {
    page: {
      type: String,
      required: true,
      default: '1'
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
      items: [],
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
      const response = await axios.get(`/api/archive?page=${this.page}&y=${this.y}&m=${this.m}`);
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      if (response.data.data == "") {
        this.$store.commit("error/setCode", NOT_FOUND);
        return false;
      }
      this.$store.commit("like/setNews", response.data.data);
      this.items = response.data;
    },
    async likeChange(data) {
      await this.$store.dispatch("like/likeChange", data);
    },
  },
};
</script>