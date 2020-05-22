<template>
  <div class="col-md-8 blog-main">
    <h3 class="pb-3 mb-4 border-bottom">user情報</h3>
    <div class="mr-2">name:{{username}}</div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
      username: ""
    };
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
  },
  methods: {
    async init() {
      await this.$store.dispatch("auth/currentUser");
      this.username = this.$store.getters["auth/username"];
    }
  },
  created() {
    this.init();
  }
};
</script>