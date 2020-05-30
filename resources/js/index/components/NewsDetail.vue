<template>
  <div class="col-md-8 blog-main">
    <div class="blog-post">
      <h2 class="blog-post-title">{{detail[0].title}}</h2>
      <div v-for="news in detail[0].add_category" :key="news.id">
        <RouterLink :to="`/category/${news.id}`">{{news.title}}</RouterLink>
      </div>
      <p class="blog-post-meta">
        {{ detail[0].created_at | moment_format("LL")}}
        <br />
        更新：{{ detail[0].updated_at | moment_format("LLL")}}
      </p>
      <Likebtn :news="news"/>
      <hr />
      <p style="white-space: pre-wrap;" v-text="detail[0].text"></p>
    </div>
  </div>
</template>

<script>
import Likebtn from "../components/Likebtn.vue";

export default {
  components: {
    Likebtn
  },
  methods: {
    like() {
      this.$emit("like", {
        id: this.detail[0].id,
        liked: this.detail[0].liked_by_user
      });
    }
  },
  props: {
    detail: {
      type: Object,
      required: true
    }
  }
};
</script>