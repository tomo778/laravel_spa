<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 font-italic border-bottom">{{y}}年{{m}}月</h3>
    <Pagination :data="items"></Pagination>
    <NewsBlock :news_arr="news_arr" />
    <Pagination :data="items"></Pagination>
    <!-- /.blog-post -->
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
  </div>
</template>

<script>
import { OK } from "../util";
import NewsBlock from "../components/NewsBlock.vue";
import Pagination from "../components/Pagination.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    NewsBlock,
    Loading,
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
      isLoading: false,
      fullPage: true
    };
  },
  created() {
    this.list();
  },
  methods: {
    async list() {
      this.isLoading = true;
      const response = await axios.get(`/api/archive?page=${this.page}&y=${this.y}&m=${this.m}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.news_arr = response.data.data;
      this.items = response.data;

      this.isLoading = false;
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