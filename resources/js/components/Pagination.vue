<template>
  <ul class="pagination">
    <li class="page-item" v-if="hasPrev">
      <RouterLink class="page-link" :to="`?p=${prev}`">{{this.prev_txt}}</RouterLink>
    </li>
    <li :class="getPageClass(page)" v-for="page in pages" :key="page">
      <RouterLink class="page-link" :to="`?p=${page}`">{{page}}</RouterLink>
    </li>
    <li class="page-item" v-if="hasNext">
      <RouterLink class="page-link" :to="`?p=${next}`">{{this.next_txt}}</RouterLink>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    data: {}
  },
  data() {
    return {
      prev_txt: "<",
      next_txt: ">"
    };
  },
  methods: {
    move(page) {
      if (!this.isCurrentPage(page)) {
        this.$emit("move-page", page);
      }
    },
    isCurrentPage(page) {
      return this.data.current_page == page;
    },
    getPageClass(page) {
      let classes = ["page-item"];
      if (this.isCurrentPage(page)) {
        classes.push("active");
      }
      return classes;
    }
  },
  computed: {
    prev() {
      return this.data.current_page - 1;
    },
    next() {
      return this.data.current_page + 1;
    },
    hasPrev() {
      return this.data.prev_page_url != null;
    },
    hasNext() {
      return this.data.next_page_url != null;
    },
    pages() {
      let pages = [];
      for (let i = 1; i <= this.data.last_page; i++) {
        pages.push(i);
      }
      return pages;
    }
  }
};
</script>