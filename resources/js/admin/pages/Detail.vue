<template>
  <div class="col-md-8 blog-main">
    <div class="blog-post">
      <h2 class="blog-post-title">{{detail.title}}</h2>
      <div v-for="news in detail.add_category" :key="news.id">
        <RouterLink :to="`/category/${news.id}`">{{news.title}}</RouterLink>
      </div>
      <p class="blog-post-meta">
        {{ detail.created_at | moment}}
        <br />
        更新：{{ detail.updated_at | moment}}
      </p>
      <p style="white-space: pre-wrap;" v-text="detail.text"></p>
    </div>
  </div>
</template>

<script>
import { OK } from "../util";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import moment from "moment";

export default {
  filters: {
    moment: function(date) {
      moment.locale("ja");
      return moment(date).format("LLLL");
    }
  },
  components: {
    Loading
  },
  props: {
    id: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      isLoading: false,
      fullPage: true,
      detail: []
    };
  },
  methods: {
    async init() {
      const response = await axios.post(`/api/detail?id=${this.id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.detail = response.data;
    }
  },
  created() {
    this.init();
  }
};
</script>