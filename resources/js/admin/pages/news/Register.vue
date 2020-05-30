<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">ニュース登録</h1>
    </div>
    <EditForm
      :formDatas="formDatas"
      :formErrors="formErrors"
      :arrayCategory="arrayCategory"
      :config="config"
    />
    <EditFooter @mode="mode($event)" :arrayMode="arrayMode" />
    <v-dialog />
  </main>
</template>

<script>
import {
  MESSAGE_ERR,
  MESSAGE_CREATE,
  MESSAGE_UPDATE,
  MESSAGE_DELETE,
  OK
} from "../../util";
import EditForm from "./components/EditForm.vue";
import EditFooter from "../../components/EditFooter.vue";

export default {
  components: {
    EditFooter,
    EditForm
  },
  data() {
    return {
      config: [],
      arrayCategory: [],
      arrayMode: [],
      formErrors: {},
      formDatas: {
        id: "",
        category: [],
        title: "",
        text: "",
        status: "1"
      }
    };
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
      this.arrayCategory = res_category.data;
      this.formDatas.title = [];
      this.formDatas.text = [];
      this.formDatas.category = [];
      this.arrayMode = ["register"];
      this.$store.commit("loadingBar/stop");
    },
    mode(event) {
      this.validation(event);
    },
    modal(mode) {
      let title = "確認a!";
      this.$modal.show("dialog", {
        title: title,
        text: "本当に宜しいでしょうか？",
        buttons: [
          {
            title: "OK",
            handler: () => {
              if (mode == "register") {
                this.register();
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
        this.formDatas
      );
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.formErrors = response.data.errors;
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
        this.formDatas
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      await this.$store.dispatch("categorys/categorys");
      this.$store.dispatch("flashMessage/showFlashMessage", MESSAGE_CREATE);
      this.$router.push(`/admin/news/edit/${response.data}`);
    },
    clearError() {
      this.formErrors = {};
    }
  }
};
</script>
