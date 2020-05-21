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
        <label for="inputEmail" class="col-sm-2 col-form-label">状態</label>
        <div class="col-sm-10">
          <div v-for="(item, index) in config.array_status" :key="index">
            <label>
              <input type="radio" v-bind:value="index" v-model="registerForm.status" />
              {{item}}
            </label>
            <br />
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputEmail" class="col-sm-2 col-form-label">カテゴリ</label>
        <div class="col-sm-10">
          <label class="mr-4" v-for="(item, index) in category_arr" :key="item.id">
            <input type="checkbox" v-bind:value="item.id" v-model="registerForm.category" />
            {{item.title}}
          </label>
          <div v-if="registerErrors">
            <div v-if="registerErrors.category">
              <div class="text-danger" v-for="msg in registerErrors.category" :key="msg">{{ msg }}</div>
            </div>
          </div>
        </div>
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
        <label for="inputEmail" class="col-sm-2 col-form-label">本文</label>
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
      category_arr: [],
      config: [],
      registerForm: {
        id: "",
        category: [],
        title: "",
        text: "",
        status: "1"
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
              if (mode  == "register") {
                this.register();
              }
              if (mode  == "update") {
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
        `/api/admin/news/validation`,
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
        `/api/admin/news/register`,
        this.registerForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      await this.$store.dispatch("categorys/categorys");
      this.$store.commit("message/setContent", {
        content: MESSAGE_CREATE
      });
      this.$router.push(`/admin/news/edit/${response.data}`);
    },
    async update() {
      this.$modal.hide("dialog");
      this.$store.commit("loading/setLoading", true);
      const response = await axios.post(
        `/api/admin/news/update`,
        this.registerForm
      );
      this.$store.commit("loading/setLoading", false);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      await this.$store.dispatch("categorys/categorys");
      this.$store.commit("message/setContent", {
        content: MESSAGE_UPDATE
      });
    },
    async init() {
      this.clearError();
      this.config = this.$store.getters["config/config"];
      this.$store.commit("loading/setLoading", true);
      const response2 = await axios.post(`/api/admin/category`);
      if (response2.status !== OK) {
        this.$store.commit("error/setCode", response2.status);
        return false;
      }
      this.category_arr = response2.data;
      if (this.id === undefined) {
        this.title = "登録";
        this.registerForm.title = [];
        this.registerForm.text = [];
        this.$store.commit("loading/setLoading", false);
        return;
      }
      const response = await axios.post(`/api/admin/news/detail/${this.id}`);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.$store.commit("loading/setLoading", false);

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
        this.clearError();
        this.init();
      },
      immediate: true
    }
  }
};
</script>
