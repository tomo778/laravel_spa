<template>
  <div class="container">
    <header class="blog-header py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <RouterLink class="blog-header-logo text-dark" :to="`/`">News site</RouterLink>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <div v-if="!isLogin">
            <RouterLink class="btn btn-sm btn-outline-secondary mr-2" :to="`/login`">ログイン</RouterLink>
            <RouterLink class="btn btn-sm btn-outline-secondary mr-2" :to="`/register`">会員登録</RouterLink>
          </div>
          <div v-if="isLogin">
            <span class="mr-2">loginuser:{{username}}</span>
            <RouterLink class="btn btn-sm btn-outline-secondary mr-2" :to="`/mypage`">マイページ</RouterLink>
            <a class="btn btn-sm btn-outline-secondary mr-2" v-on:click="logout">ログアウト</a>
          </div>
        </div>
      </div>
    </header>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
      isLogin: false,
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
      this.isLogin = this.$store.getters["auth/check"];
      this.username = this.$store.getters["auth/username"];
    },
    async logout() {
      await this.$store.dispatch("auth/logout");
      // メッセージ登録
      // this.$store.commit("message/setContent", {
      //   content: "ログアウトました！",
      //   timeout: 6000
      // });
      // this.$router.go({ name: '/' });

      if (this.apiStatus) {
        this.$router.go({ name: '/' });
      }
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
