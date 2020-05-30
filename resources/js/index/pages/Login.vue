<template>
  <div class="col-md-8 blog-main">
    <h4 class="mb-3">ログイン</h4>
    <div class="mb-3">
      <label for="email">Email</label>
      <input
        type="email"
        v-model="loginForm.email"
        class="form-control"
        id="email"
        placeholder="you@example.com"
      />
      <ErrMessage :Errors="loginErrors" :name="'email'"/>
    </div>
    <div class="mb-3">
      <label for="password">パスワード</label>
      <input type="password" class="form-control" id="password" v-model="loginForm.password" />
      <ErrMessage :Errors="loginErrors" :name="'password'"/>
    </div>
    <button class="btn btn-primary btn-lg btn-block" v-on:click="login">ログイン</button>
  </div>
</template>

<script>
import { OK } from "../util";
import { mapState } from "vuex";
import ErrMessage from "../components/ErrMessage.vue";

export default {
  components: {
    ErrMessage
  },
  data() {
    return {
      loginForm: {
        email: "",
        password: ""
      }
    };
  },
  computed: mapState({
    apiStatus: state => state.auth.apiStatus,
    loginErrors: state => state.auth.loginErrorMessages
  }),
  created() {
    this.clearError();
  },
  methods: {
    async login() {
      this.$store.commit("loadingBar/start");
      await this.$store.dispatch("auth/login", this.loginForm);
      this.$store.commit("loadingBar/stop");
      if (this.apiStatus) {
        this.$router.push("/");
      }
    },
    clearError() {
      this.$store.commit("auth/setLoginErrorMessages", null);
    }
  },
};
</script>