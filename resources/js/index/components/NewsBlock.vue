<template>
  <div class="blog-post">
    <h2 class="blog-post-title">{{ news.title }}</h2>
    <div v-for="category in news.add_category" :key="category.id">
      <RouterLink class="edit" :to="`/category/${category.id}`">{{category.title}}</RouterLink>
    </div>
    <p class="blog-post-meta">
      {{ news.created_at | moment}}
      <br />
      更新：{{ news.updated_at | moment}}
    </p>
    <p>{{ news.text }}</p>
    <RouterLink class="btn btn-primary btn-sm" :to="`/detail/${news.id}`">詳細へ</RouterLink>
      <button v-if="!news.liked_by_user" @click.prevent="like" type="button" class="btn btn-secondary btn-sm">
        お気に入り登録
        <span class="badge badge-light">{{ news.likes_count }}</span>
      </button>
      <button v-if="news.liked_by_user" @click.prevent="like" type="button" class="btn btn-primary btn-sm">
        お気に入り解除
        <span class="badge badge-light">{{ news.likes_count }}</span>
      </button>
    <hr />
  </div>
</template>

<script>
import moment from "moment";
export default {
  filters: {
    moment: function(date) {
      moment.locale("ja");
      return moment(date).format("LLLL");
    }
  },
  methods: {
    like() {
      this.$emit("like", {
        id: this.news.id,
        liked: this.news.liked_by_user
      });
    }
  },
  props: {
    news: {
      type: Object,
      required: true
    }
  }
};
</script>