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
          <div v-for="(item, index) in status" :key="index">
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
          <div v-for="(item, index) in category_arr" :key="item.id">
            <label>
              <input type="checkbox" v-bind:value="item.id" v-model="registerForm.category" />
              {{item.title}}
            </label>
          </div>
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
import {
  MESSAGE_ERR,
  MESSAGE_CREATE,
  MESSAGE_UPDATE,
  OK,
  STATUS
} from "../../util";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import Footer from "../../components/Footer.vue";

export default {
  components: {
    Loading,
    Footer
  },
  props: {
    id: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      title: "",
      isLoading: false,
      fullPage: true,
      registerErrors: [],
      category_arr: [],
      status: STATUS,
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
    async register() {
      const response = await axios.post(
        `/api/admin/news/register`,
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
          content: MESSAGE_CREATE
        });
        this.$router.push(`/admin/news/edit/${response.data}`);
      }
    },
    async update() {
      const response = await axios.post(
        `/api/admin/news/update`,
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
      const response2 = await axios.post(`/api/admin/category`);
      if (response2.status !== OK) {
        this.$store.commit("error/setCode", response2.status);
        return false;
      }
      this.category_arr = response2.data.data;

      if (this.id === undefined) {
        this.title = "登録";
        this.registerForm.title = null;
        this.registerForm.text = null;
        return;
      }

      const response = await axios.post(`/api/admin/news/detail/${this.id}`);
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
