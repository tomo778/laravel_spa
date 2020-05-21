<template>
  <div>
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
              <RouterLink class="btn btn-sm btn-outline-secondary mr-2" :to="`/mypage`">マイページ</RouterLink>
              <a class="btn btn-sm btn-outline-secondary mr-2" v-on:click="logout">ログアウト</a>
            </div>
          </div>
        </div>
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

        <MypageNav />
        <!-- /.blog-sidebar -->
      </div>
      <!-- /.row -->
    </main>
    <!-- /.container -->

    <!-- /.blog-post -->
    <footer class="blog-footer">
      <a href="#">Back to top</a>
    </footer>
  </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR, OK } from "../util";
import MypageNav from "../components/MypageNav.vue";
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
    },
    errorCode() {
      return this.$store.state.error.code;
    },
    money() {
      return this.$store.getters["categorys/categorys"];
    }
  },
  data() {
    return {
      categorys: [],
      archives: []
    };
  },
  components: {
    MypageNav
  },
  metaInfo: {
    title: "top"
    //titleTemplate: "%s - Yay!",
    //link: [{ rel: "stylesheet", href: "/css/index.css" }]
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
    },
    async logout() {
      await this.$store.dispatch("auth/logout");

      // メッセージ登録
      this.$store.commit("message/setContent", {
        content: "ログアウトました！",
        timeout: 6000
      });

      if (this.apiStatus) {
        this.$router.push("/");
      }
    }
  },
  created() {
    this.init();
  },
  watch: {
    money() {
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