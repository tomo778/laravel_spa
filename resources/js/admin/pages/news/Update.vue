<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">ニュース更新</h1>
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
import DialogMixin from "../../mixins/DialogMixin";

export default {
  components: {
    EditForm,
    EditFooter
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
      formErrors: {},
      arrayCategory: [],
      arrayMode: [],
      config: [],
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
      const res_detail = await axios.post(`/api/admin/news/detail/${this.id}`);
      if (res_detail.status !== OK) {
        this.$store.commit("error/setCode", res_detail.status);
        return false;
      }
      this.formDatas = res_detail.data;
      this.arrayMode = ["update", "delete"];
      this.$store.commit("loadingBar/stop");
    },
    mode(event) {
      if (event == "update") {
        this.validation(event);
      }
      if (event == "delete") {
        this.modal(event);//mixins
      }
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
      this.modal(mode);//mixins
    },
    async update() {
      this.hideDialog();//mixins
      this.$store.commit("loadingBar/start");
      const response = await axios.post(
        `/api/admin/news/update`,
        this.formDatas
      );
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      await this.$store.dispatch("categorys/categorys");
      this.$store.dispatch("flashMessage/showFlashMessage", MESSAGE_UPDATE);
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
    clearError() {
      this.formErrors = {};
    }
  },
  mixins: [DialogMixin]
};
</script>
