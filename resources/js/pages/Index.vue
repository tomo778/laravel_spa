<template>
  <div class="col-md-8 blog-main">
    <NewsBlock :news_arr="news_arr" />
    <Pagination :data="items"></Pagination>
    <!-- /.blog-post -->
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
  </div>
</template>

<script>
import { OK } from "../util";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";

export default {
  components: {
    Pagination,
    NewsBlock,
    Loading
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
      isLoading: false,
      fullPage: true
    };
  },
  methods: {
    async list() {
      this.isLoading = true;
      const response = await axios.get(`/api/index?page=${this.page}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.news_arr = response.data.data;
      this.items = response.data;
      this.isLoading = false;
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