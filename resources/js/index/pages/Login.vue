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
    </div>
    <div class="mb-3">
      <label for="password">パスワード</label>
      <input type="password" class="form-control" id="password" v-model="loginForm.password" />
    </div>
    <hr class="mb-4" />
    <div v-if="loginErrors" class="errors">
      <ul v-if="loginErrors.email">
        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="loginErrors.password">
        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <button class="btn btn-primary btn-lg btn-block" v-on:click="login">ログイン</button>
  </div>
</template>

<script>
import { OK } from "../util";
import { mapState } from "vuex";

export default {
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
  methods: {
    async login() {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("auth/login", this.loginForm);

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push("/");
      }
    },
    clearError() {
      this.$store.commit("auth/setLoginErrorMessages", null);
    }
  },
  created() {
    this.clearError();
  }
};
</script>