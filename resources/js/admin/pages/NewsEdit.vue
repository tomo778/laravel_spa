<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">{{this.title}}</h1>
    </div>
    <table class="table admin-table">
      <tr v-if="this.id">
        <th>id</th>
        <td>
          <div>{{this.id}}</div>
          <input type="hidden" name="id" v-model="registerForm.id" />
        </td>
      </tr>
      <tr>
        <th>状態</th>
        <td>
          <div v-for="(item, index) in config.array_status" :key="index">
            <label>
              <input type="radio" v-bind:value="index" v-model="registerForm.status" />
              {{item}}
            </label>
          </div>
        </td>
      </tr>
      <tr>
        <th>カテゴリ</th>
        <td>
          <label class="mr-4" v-for="(item, index) in category_arr" :key="item.id">
            <input type="checkbox" v-bind:value="item.id" v-model="registerForm.category" />
            {{item.title}}
          </label>
          <ErrMessage :Errors="registerErrors" :name="'category'" />
        </td>
      </tr>
      <tr>
        <th>タイトル</th>
        <td>
          <input type="text" class="form-control" name="title" v-model="registerForm.title" />
          <ErrMessage :Errors="registerErrors" :name="'title'" />
        </td>
      </tr>
      <tr>
        <th>本文</th>
        <td>
          <textarea class="form-control" v-model="registerForm.text" cols="30" rows="10"></textarea>
          <ErrMessage :Errors="registerErrors" :name="'text'" />
        </td>
      </tr>
    </table>
    <EditFooter @mode="mode($event)" :footer_mode="footer_mode" />
    <v-dialog />
  </main>
</template>

<script>
import { mapState } from "vuex";
import { MESSAGE_ERR, MESSAGE_CREATE, MESSAGE_UPDATE, MESSAGE_DELETE, OK } from "../util";
import ErrMessage from "../components/ErrMessage.vue";
import EditFooter from "../components/EditFooter.vue";

export default {
  components: {
    EditFooter,
    ErrMessage
  },
  props: {
    id: {
      type: String,
      required: false
      //default: 1
    }
  },
  data() {
    return {
      title: "",
      registerErrors: {},
      category_arr: [],
      footer_mode: [],
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
  watch: {
    $route: {
      async handler() {
        this.clearError();
        this.init();
      },
      immediate: true
    }
  },
  created() {
    this.init();
  },
  methods: {
    async init() {
      this.$store.commit("loadingBar/start");
      this.clearError();
      this.config = this.$store.getters["config/config"];
      const res_category = await axios.post(`/api/admin/category`);
      if (res_category.status !== OK) {
        this.$store.commit("error/setCode", res_category.status);
        return false;
      }
      this.category_arr = res_category.data;
      if (this.id === undefined) {
        this.init_register();
      } else {
        this.init_update();
      }
      this.$store.commit("loadingBar/stop");
    },
    init_register() {
      this.title = "登録";
      this.registerForm.title = [];
      this.registerForm.text = [];
      this.registerForm.category = [];
      this.footer_mode = ["register"];
    },
    async init_update() {
      this.title = "更新";
      const res_detail = await axios.post(`/api/admin/news/detail/${this.id}`);
      if (res_detail.status !== OK) {
        this.$store.commit("error/setCode", res_detail.status);
        return false;
      }
      this.registerForm = res_detail.data;
      this.footer_mode = ["update", "delete"];
    },
    mode(event) {
      if (event == "register" || event == "update") {
        this.validation(event);
      }
      if (event == "delete") {
        this.modal(event);
      }
    },
    async delete(event) {
      const res = await axios.post(`/api/admin/news/delete/${this.id}`);
      if (res.status !== OK) {
        this.$store.commit("error/setCode", res.status);
        return false;
      }
      this.$store.dispatch("flashMessage/showFlashMessage", MESSAGE_DELETE);
      this.$router.push(`/admin/news/`);
    },
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
              if (mode == "delete") {
                this.delete();
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
      this.$store.commit("loadingBar/start");
      const response = await axios.post(
        `/api/admin/news/validation`,
        this.registerForm
      );
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.registerErrors = response.data.errors;
        this.$store.commit("error/setCode", response.status);
        this.$store.dispatch("flashMessage/showFlashMessage", MESSAGE_ERR);
        return false;
      }
      this.clearError();
      this.modal(mode);
    },
    async register() {
      this.$modal.hide("dialog");
      this.$store.commit("loadingBar/start");
      const response = await axios.post(
        `/api/admin/news/register`,
        this.registerForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      await this.$store.dispatch("categorys/categorys");
      this.$store.dispatch("flashMessage/showFlashMessage", MESSAGE_CREATE);
      this.$router.push(`/admin/news/edit/${response.data}`);
    },
    async update() {
      this.$modal.hide("dialog");
      this.$store.commit("loadingBar/start");
      const response = await axios.post(
        `/api/admin/news/update`,
        this.registerForm
      );
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      await this.$store.dispatch("categorys/categorys");
      this.$store.dispatch("flashMessage/showFlashMessage", MESSAGE_UPDATE);
    },
    clearError() {
      this.registerErrors = {};
    }
  }
};
</script>
