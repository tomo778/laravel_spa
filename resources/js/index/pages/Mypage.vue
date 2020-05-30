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
  created() {
    this.init();
  },
  methods: {
    async init() {
      this.$store.commit("loadingBar/start");
      await this.$store.dispatch("auth/currentUser");
      this.$store.commit("loadingBar/stop");
      this.username = this.$store.getters["auth/username"];
    }
  },
};
</script>