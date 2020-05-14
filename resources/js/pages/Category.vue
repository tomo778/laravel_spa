<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 font-italic border-bottom">category: {{category.title}}</h3>
    <Pagination :data="items"></Pagination>
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
    id: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      category: [],
      items: [],
      news_arr: [],
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
      const response = await axios.get(
        `/api/category?page=${this.page}&id=${this.id}`
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      var categorys = this.$store.getters["categorys/categorys"];
      categorys.forEach(e => {
        if (e.id == this.id) {
          this.category = e;
        }
      });
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