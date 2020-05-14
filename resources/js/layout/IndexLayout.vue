<template>
  <div>
    <div class="container">
      <header class="blog-header py-3">
        <RouterLink class="blog-header-logo text-dark" :to="`/`">News site</RouterLink>
      </header>
      <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
          <RouterLink
            class="p-2 text-muted"
            v-for="category in categorys"
            :key="category.id"
            :to="`/category/${category.id}`"
          >{{category.title}}</RouterLink>
        </nav>
      </div>
    </div>

    <main role="main" class="container my-5">
      <div class="row">
        <RouterView />
        <!-- /.blog-main -->

        <SideNav :categorys="categorys" :archives="archives" />
        <!-- /.blog-sidebar -->
      </div>
      <!-- /.row -->
    </main>
    <!-- /.container -->

    <footer class="blog-footer">
      <a href="#">Back to top</a>
    </footer>
  </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR, OK } from "../util";
import SideNav from "../components/SideNav.vue";

export default {
  data() {
    return {
      categorys: [],
      archives: []
    };
  },
  components: {
    SideNav
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code;
    },
    money() {
      return this.$store.getters["categorys/categorys"];
    }
  },
  metaInfo: {
    title: "top",
    //titleTemplate: "%s - Yay!",
    link: [{ rel: "stylesheet", href: "/css/index.css" }]
  },
  methods: {
    async init() {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("categorys/categorys");
      this.categorys = this.$store.getters["categorys/categorys"];
      //
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
    money() {
        console.log("変更されました");
        this.categorys = this.$store.getters["categorys/categorys"];
    },
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
