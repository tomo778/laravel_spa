<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">{{this.title}}</h1>
    </div>

    <form>
      <div v-if="this.id" class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">id</label>
        <div class="col-sm-10">{{this.id}}</div>
        <input type="hidden" name="id" v-model="registerForm.id" />
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">タイトル</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="title" v-model="registerForm.title" />
          <div v-if="registerErrors">
            <div v-if="registerErrors.title">
              <div class="text-danger" v-for="msg in registerErrors.title" :key="msg">{{ msg }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">説明文</label>
        <div class="col-sm-10">
          <textarea class="form-control" v-model="registerForm.text" cols="30" rows="10"></textarea>
          <div v-if="registerErrors">
            <div v-if="registerErrors.text">
              <div class="text-danger" v-for="msg in registerErrors.text" :key="msg">{{ msg }}</div>
            </div>
          </div>
        </div>
      </div>

      <footer class="fixed-bottom bg-white p-2 text-center">
        <button
          v-if="!this.id"
          @click.prevent="validation('register')"
          class="btn btn-primary edit_btn"
        >{{this.title}}する</button>
        <button
          v-if="this.id"
          @click.prevent="validation('update')"
          class="btn btn-primary edit_btn"
        >{{this.title}}する</button>
      </footer>
    </form>
    <v-dialog />
  </main>
</template>

<script>
import { mapState } from "vuex";
import { MESSAGE_ERR, MESSAGE_CREATE, MESSAGE_UPDATE, OK } from "../util";

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
        title: "",
        text: "",
        role: ""
      }
    };
  },
  methods: {
    modal(mode) {
      this.$modal.show("dialog", {
        title: "確認!",
        text: "本当に宜しいでしょうか？",
        buttons: [
          {
            title: "OK",
            handler: () => {
              if (mode == "register") {
                this.register();
              }
              if (mode == "update") {
                this.update();
              }
            }
          },
          {
            title: "Close"
          }
        ]
      });
    },
    async validation(mode) {
      this.$store.commit("loading/setLoading", true);
      const response = await axios.post(
        `/api/admin/category/validation`,
        this.registerForm
      );
      this.$store.commit("loading/setLoading", false);
      if (response.status !== OK) {
        this.registerErrors = response.data.errors;
        this.$store.commit("error/setCode", response.status);
        this.$store.commit("message/setContent", {
          content: MESSAGE_ERR
        });
        return false;
      }
      this.clearError();
      this.modal(mode);
    },
    async register() {
      this.$modal.hide("dialog");
      this.$store.commit("loading/setLoading", true);
      const response = await axios.post(
        `/api/admin/category/register`,
        this.registerForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.$store.commit("message/setContent", {
        content: MESSAGE_CREATE
      });
      this.$router.push(`/admin/category/edit/${response.data}`);
    },
    async update() {
      this.$modal.hide("dialog");
      this.$store.commit("loading/setLoading", true);
      const response = await axios.post(
        `/api/admin/category/update`,
        this.registerForm
      );
      this.$store.commit("loading/setLoading", false);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.$store.commit("message/setContent", {
        content: MESSAGE_UPDATE
      });
    },
    async init() {
      this.clearError();
      if (this.id === undefined) {
        this.title = "登録";
        this.registerForm = [];
        return false;
      }
      this.$store.commit("loading/setLoading", true);
      const response = await axios.post(
        `/api/admin/category/detail/${this.id}`
      );
      this.$store.commit("loading/setLoading", false);

      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }

      this.title = "更新";
      this.registerForm = response.data;
    },
    clearError() {
      this.registerErrors = [];
    }
  },
  created() {
    this.init();
  },
  watch: {
    $route: {
      async handler() {
        this.init();
      },
      immediate: true
    }
  }
};
</script>
