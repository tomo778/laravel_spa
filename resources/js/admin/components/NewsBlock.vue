<template>
  <div>
    <div v-for="news in news_arr" :key="news.id" class="blog-post">
      <h2 class="blog-post-title">{{ news.title }}</h2>
      <div v-for="news in news.add_category" :key="news.id">
        <RouterLink class="edit" :to="`/category/${news.id}`">{{news.title}}</RouterLink>
      </div>
      <p class="blog-post-meta">
        {{ news.created_at | moment}}
        <br />
        更新：{{ news.updated_at | moment}}
      </p>
      <p>{{ news.text }}</p>
      <RouterLink class="edit" :to="`/detail/${news.id}`">詳細</RouterLink>
      <hr />
    </div>
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
  props: ["news_arr"]
};
</script>