<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">{{this.title}}</h1>
    </div>

    <form>
      <!-- <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">ロール</label>
        <div class="col-sm-10">
          <label>
            <input type="radio" name="role" value="1" />管理者
          </label>
          <label>
            <input type="radio" name="role" value="2" />スタッフ
          </label>
        </div>
      </div>-->
      <div v-if="this.id" class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">id</label>
        <div class="col-sm-10">{{this.id}}</div>
        <input type="hidden" name="id" v-model="registerForm.id" />
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">名前</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="name" v-model="registerForm.name" />
          <div v-if="registerErrors">
            <div v-if="registerErrors.name">
              <div class="text-danger" v-for="msg in registerErrors.name" :key="msg">{{ msg }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">email</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="email" v-model="registerForm.email" />
          <div v-if="registerErrors">
            <div v-if="registerErrors.email">
              <div class="text-danger" v-for="msg in registerErrors.email" :key="msg">{{ msg }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">パスワード</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="password" v-model="registerForm.password" />
          <div v-if="registerErrors">
            <div v-if="registerErrors.password">
              <div class="text-danger" v-for="msg in registerErrors.password" :key="msg">{{ msg }}</div>
            </div>
          </div>
        </div>
      </div>
      <footer class="fixed-bottom bg-white p-2 text-center">
        <button
          v-if="!this.id"
          @click.prevent="register"
          class="btn btn-primary edit_btn"
        >{{this.title}}する</button>
        <button
          v-if="this.id"
          @click.prevent="update"
          class="btn btn-primary edit_btn"
        >{{this.title}}する</button>
      </footer>
    </form>
  </main>
</template>

<script>
import { mapState } from "vuex";
import { MESSAGE_ERR, MESSAGE_CREATE, MESSAGE_UPDATE, OK, CREATED } from "../util";


export default {
  props: {
    id: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      title: "",
      registerErrors: [],
      registerForm: {
        id: "",
        name: "",
        email: "",
        password: ""
      }
    };
  },
  methods: {
    async register() {
      const response = await axios.post("/api/register", this.registerForm);
      if (response.status !== CREATED) {
        this.registerErrors = response.data.errors;
        this.$store.commit("error/setCode", response.status);
        this.$store.commit("message/setContent", {
          content: MESSAGE_ERR
        });
        return false;
      } else {
        this.$store.commit("message/setContent", {
          content: MESSAGE_CREATE
        });
        this.$router.push(`/admin/staff/edit/${response.data.id}`);
      }
    },
    async update() {
      const response = await axios.post(
        `/api/admin/user/update`,
        this.registerForm
      );

      if (response.status !== OK) {
        this.registerErrors = response.data.errors;
        this.$store.commit("error/setCode", response.status);
        this.$store.commit("message/setContent", {
          content: MESSAGE_ERR
        });
        return false;
      } else {
        this.$store.commit("message/setContent", {
          content: MESSAGE_UPDATE
        });
        this.clearError();
      }
    },
    async init() {
      if (this.id === undefined) {
        this.title = "登録";
        this.registerForm.name = null;
        this.registerForm.email = null;
        return;
      }

      const response = await axios.post(`/api/admin/user/detail/${this.id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.title = "更新";
      this.registerForm = response.data;
    },
    clearError() {
      this.$store.commit("auth/setRegisterErrorMessages", null);
    }
  },
  created() {
    this.clearError();
    this.init();
  },
  watch: {
    $route: {
      async handler() {
        this.clearError();
        this.init();
      },
      immediate: true
    }
  }
};
</script>
