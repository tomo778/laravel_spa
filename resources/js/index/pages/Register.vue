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
        <div class="invalid-feedback" style="width: 100%;">Your username is required.</div>
      </div>
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
      <div class="invalid-feedback">Please enter a valid email address for shipping updates.</div>
    </div>
    <div class="mb-3">
      <label for="password">
        パスワード
        <span class="text-muted">(3文字以上)</span>
      </label>
      <input type="password" class="form-control" id="password" v-model="registerForm.password" />
    </div>
    <hr class="mb-4" />
    <div v-if="registerErrors" class="errors">
      <ul v-if="registerErrors.name">
        <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="registerErrors.email">
        <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="registerErrors.password">
        <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>
    <button class="btn btn-primary btn-lg btn-block" v-on:click="register">登録する</button>
  </div>
</template>

<script>
import { OK } from "../util";
import { mapState } from "vuex";

export default {
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
  methods: {
    async register() {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch("auth/register", this.registerForm);

      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push("/");
      }
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
};
</script>