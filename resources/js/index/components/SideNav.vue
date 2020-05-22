<template>
  <aside class="col-md-4 blog-sidebar">
    <div class="p-3 mb-3 bg-light rounded">
      <h4 class="font-italic">About</h4>
      <p class="mb-0">
        サイトの説明サイトの説明サイトの説明サイトの説明サイトの説明サイトの説明
        サイトの説明サイトの説明サイトの説明サイトの説明
        サイトの説明サイトの説明サイトの説明
      </p>
    </div>

    <div class="p-3">
      <h4 class="font-italic">Categorys</h4>
      <ol class="list-unstyled mb-0">
        <li v-for="category in categorys" :key="category.id">
          <RouterLink :to="`/category/${category.id}`">{{category.title}}</RouterLink>
        </li>
      </ol>
    </div>
    <div class="p-3">
      <h4 class="font-italic">Archives</h4>
      <ul class="list-unstyled mb-0">
        <li v-for="(archive, key) in archives" :key="archive.id">
          <RouterLink :to="`/archive/${archive.year}/${archive.month}`">{{archive.year}}年{{archive.month}}月</RouterLink>({{archive.post_count}})
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR, OK } from "../util";

export default {
  data() {
    return {
      categorys: [],
      archives: []
    };
  },
  methods: {
    async init() {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("categorys/categorys");
      this.categorys = this.$store.getters["categorys/categorys"];
      const response = await axios.post(`/api/get/archives`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.archives = response.data;
    }
  },
  created() {
    this.init();
  },
  watch: {
    $route() {
      this.init();
    }
  }
};
</script>