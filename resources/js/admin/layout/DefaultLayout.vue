<template>
  <div>
    <FlashMessage />
    <LoadingBar />
    <a id="skippy" class="sr-only sr-only-focusable" href="#content">
      <div class="container">
        <span class="skiplink-text">Skip to main content</span>
      </div>
    </a>
    <Navbar />
    <div class="container-fluid">
      <div class="row">
        <SideNav />
        <RouterView />
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "../components/Navbar.vue";
import FlashMessage from "../components/FlashMessage.vue";
import SideNav from "../components/SideNav.vue";
import LoadingBar from "../components/LoadingBar.vue";

import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from "../util";
import feather from "feather-icons";

export default {
  components: {
    SideNav,
    FlashMessage,
    Navbar,
    LoadingBar
  },
  metaInfo: {
    link: [{ rel: "stylesheet", href: "/css/app.css" }],
    // script: [
    //   {
    //     src: "/js/app.css",
    //     async: true,
    //     defer: true
    //   }
    // ],
    titleTemplate: "管理画面"
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    }
  },
  mounted() {
    feather.replace();
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
