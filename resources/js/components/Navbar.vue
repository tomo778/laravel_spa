<template>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <RouterLink class="navbar-brand col-sm-3 col-md-2 mr-0" to="/" target="_blank">Company name</RouterLink>
    <ul v-if="isLogin" class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <span @click="logout" class="nav-link">ログアウト</span>
      </li>
    </ul>
  </nav>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: "auth/check"
    }),
    isLogin() {
      return this.$store.getters["auth/check"];
    },
    username() {
      return this.$store.getters["auth/username"];
    }
  },
  methods: {
    async logout() {
      await this.$store.dispatch("auth/logout");

      // メッセージ登録
      this.$store.commit("message/setContent", {
        content: "ログアウトました！",
        timeout: 6000
      });

      if (this.apiStatus) {
        this.$router.push("/admin/login");
      }
    }
  }
};
</script>
