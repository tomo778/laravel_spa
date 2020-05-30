<template>
  <div class="col-md-8 blog-main">
    <div class="blog-post">
      <h2 class="blog-post-title">{{detail.title}}</h2>
      <div v-for="news in detail.add_category" :key="news.id">
        <RouterLink :to="`/category/${news.id}`">{{news.title}}</RouterLink>
      </div>
      <p class="blog-post-meta">
        {{ detail.created_at | moment_format("LL")}}
        <br />
        更新：{{ detail.updated_at | moment_format("LLL")}}
      </p>
      <Likebtn :news="detail" v-bind:detail="true"/>
      <hr />
      <p style="white-space: pre-wrap;" v-text="detail.text"></p>
    </div>
  </div>
</template>

<script>
import { OK, NOT_FOUND } from "../util";
import Likebtn from "../components/Likebtn.vue";
import { mapState } from "vuex";

export default {
  metaInfo() {
    return {
      title: 11,
      titleTemplate: "%s - sitetitle"
    };
  },
  components: {
    Likebtn
  },
  props: {
    id: {
      type: String,
      required: false
    }
  },
  computed: mapState({
    detail: state => state.like.detail
  }),
  created() {
    this.init();
  },
  methods: {
    async init() {
      this.$store.commit("loadingBar/start");
      const response = await axios.post(`/api/detail?id=${this.id}`);
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      if (response.data == "") {
        this.$store.commit("error/setCode", NOT_FOUND);
        return false;
      }
      this.$store.commit("like/setDetail", response.data);
    }
  },
};
</script>