<template>
  <div class="blog-post">
    <h2 class="blog-post-title">{{ news.title }}</h2>
    <div v-for="category in news.add_category" :key="category.id">
      <RouterLink class="edit" :to="`/category/${category.id}`">{{category.title}}</RouterLink>
    </div>
    <p class="blog-post-meta">
      {{ news.created_at | moment_format("LL")}}
      <br />
      更新：{{ news.updated_at | moment_format("LLL")}}
    </p>
    <p>{{ news.text | truncate(100, '...')}}</p>
    <RouterLink class="btn btn-primary btn-sm" :to="`/detail/${news.id}`">詳細へ</RouterLink>
    <Likebtn :news="news"/>
    <hr />
  </div>
</template>

<script>
import Likebtn from "../components/Likebtn.vue";

export default {
  components: {
    Likebtn
  },
  props: {
    news: {
      type: Object,
      required: true
    }
  }
};
</script>