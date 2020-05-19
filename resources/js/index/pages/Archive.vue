<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 font-italic border-bottom">{{y}}年{{m}}月</h3>
    <Pagination :data="items"></Pagination>
    <NewsBlock :news_arr="news_arr" />
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