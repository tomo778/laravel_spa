<template>
  <div class="col-md-8 blog-main">
    <NewsBlock v-for="news in news_arr" :key="news.id" :news="news"/>
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
      title: "sitetitle",
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
      required: false,
      default: 1
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
  created() {
    this.init();
  },
  watch: {
    $route: {
      async handler() {
        await this.init();
      },
      immediate: true
    }
  },
  methods: {
    async init() {
      this.$store.commit("loadingBar/start");
      const response = await axios.get(`/api/index?page=${this.page}`);
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.$store.commit("like/setNews", response.data.data);
      this.items = response.data;
    },
  },
};
</script>