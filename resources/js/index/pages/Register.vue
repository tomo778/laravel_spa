<template>
  <div class="col-md-8 blog-main">
    <h4 class="mb-3">会員登録</h4>
    <div class="mb-3">
      <label for="username">お名前</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">@</span>
        </div>
        <input
          type="text"
          v-model="registerForm.name"
          class="form-control"
          id="username"
          placeholder="お名前"
        />
      </div>
      <ErrMessage :Errors="registerErrors" :name="'name'" />
    </div>
    <div class="mb-3">
      <label for="email">Email</label>
      <input
        type="email"
        v-model="registerForm.email"
        class="form-control"
        id="email"
        placeholder="you@example.com"
      />
      <ErrMessage :Errors="registerErrors" :name="'email'" />
    </div>
    <div class="mb-3">
      <label for="password">
        パスワード
        <span class="text-muted">(3文字以上)</span>
      </label>
      <input type="password" class="form-control" id="password" v-model="registerForm.password" />
      <ErrMessage :Errors="registerErrors" :name="'password'" />
    </div>
    <button class="btn btn-primary btn-lg btn-block" v-on:click="register">登録する</button>
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
      registerForm: {
        name: "",
        email: "",
        password: ""
      }
    };
  },
  computed: mapState({
    apiStatus: state => state.auth.apiStatus,
    registerErrors: state => state.auth.registerErrorMessages
  }),
  created() {
    this.clearError();
  },
  methods: {
    async register() {
      this.$store.commit("loadingBar/start");
      await this.$store.dispatch("auth/register", this.registerForm);
      this.$store.commit("loadingBar/stop");
      if (this.apiStatus) {
        this.$router.push("/");
      }
    },
    clearError() {
      this.$store.commit("auth/setLoginErrorMessages", null);
      this.$store.commit("auth/setRegisterErrorMessages", null);
    }
  }
};
</script>