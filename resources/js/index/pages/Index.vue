<template>
  <div class="col-md-8 blog-main">
    <NewsBlock :news_arr="news_arr" />
    <Pagination :data="items"></Pagination>
  </div>
</template>

<script>
import { OK } from "../util";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";

export default {
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
      items: [],
      fullPage: true
    };
  },
  methods: {
    async list() {
      const response = await axios.get(`/api/index?page=${this.page}`);
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