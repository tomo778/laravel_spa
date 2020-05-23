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
      <button
        v-if="!detail.liked_by_user"
        @click.prevent="onLikeClick(false)"
        type="button"
        class="btn btn-secondary btn-sm"
      >
        お気に入り登録
        <span class="badge badge-light">{{ detail.likes_count }}</span>
      </button>
      <button
        v-if="detail.liked_by_user"
        @click.prevent="onLikeClick(true)"
        type="button"
        class="btn btn-primary btn-sm"
      >
        お気に入り解除
        <span class="badge badge-light">{{ detail.likes_count }}</span>
      </button>
      <hr>
      <p style="white-space: pre-wrap;" v-text="detail.text"></p>
    </div>
  </div>
</template>

<script>
import { OK, NOT_FOUND } from "../util";

export default {
  metaInfo() {
    return {
      title: this.detail.title,
      titleTemplate: "%s - sitetitle"
    };
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
      if (response.data == "") {
        this.$store.commit("error/setCode", NOT_FOUND);
        return false;
      }
      this.detail = response.data;
    },
    onLikeClick(liked) {
      if (!this.$store.getters["auth/check"]) {
        alert("いいね機能を使うにはログインしてください。");
        return false;
      }
      if (liked) {
        this.unlike();
      } else {
        this.like();
      }
    },
    async like() {
      const response = await axios.put(`/api/news/like/${this.detail.id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.detail.likes_count += 1;
      this.detail.liked_by_user = true;
    },
    async unlike(id) {
      const response = await axios.delete(`/api/news/like/${this.detail.id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.detail.likes_count -= 1;
      this.detail.liked_by_user = false;
    }
  },
  created() {
    this.init();
  }
};
</script>