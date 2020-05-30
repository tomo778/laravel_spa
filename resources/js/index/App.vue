<template>
  <div>
    <LoadingBar />
    <MainHeader />
    <main role="main" class="container my-5">
      <div class="row">
        <RouterView />
        <component v-bind:is="layout" />
      </div>
    </main>
    <MainFooter />
    <Message />
  </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR, OK } from "./util";
import SideNav from "./components/SideNav.vue";
import MainHeader from "./components/MainHeader.vue";
import MainFooter from "./components/MainFooter.vue";
import MypageNav from "./components/MypageNav.vue";
import Message from "./components/Message.vue";
import LoadingBar from "./components/LoadingBar.vue";

export default {
  components: {
    LoadingBar,
    Message,
    MypageNav,
    SideNav,
    MainHeader,
    MainFooter
  },
  computed: {
    layout: {
      cache: false,
      get: function() {
        return this.$route.meta.layout || "SideNav";
      }
    },
    errorCode() {
      return this.$store.state.error.code;
    }
  },
  watch: {
    errorCode: {
      async handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get("/api/refresh-token");
          // ストアのuserをクリア
          this.$store.commit("auth/setUser", null);
          // ログイン画面へ
          this.$router.push("/login");
        } else if (val === NOT_FOUND) {
          this.$router.push("/not-found");
        }
      },
      immediate: true
    },
    $route() {
      this.$store.commit("error/setCode", null);
    }
  }
};
</script>
