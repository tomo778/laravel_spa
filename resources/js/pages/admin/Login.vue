<template>
  <form class="form-signin" @submit.prevent="login">
    <h1 class="h3 mb-3 font-weight-normal">ログイン</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input
      type="email"
      id="inputEmail"
      class="form-control"
      placeholder="Email address"
      v-model="loginForm.email"
    />
    <label for="inputPassword" class="sr-only">Password</label>
    <input
      type="password"
      id="inputPassword"
      class="form-control"
      placeholder="Password"
      v-model="loginForm.password"
    />
    <div v-if="loginErrors" class="errors">
      <ul v-if="loginErrors.email">
        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="loginErrors.password">
        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <!-- <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me" /> Remember me
        </label>
    </div>-->
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
  </form>
</template>

<script>
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
        this.$router.push("/admin");
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
