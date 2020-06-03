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
import DialogMixin from "../../mixins/DialogMixin";
import { mapGetters, mapActions } from "vuex";

export default {
  components: {
    EditFooter,
    EditForm
  },
  data() {
    return {
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
  computed: {
    ...mapGetters({
      config: "config/config"
    })
  },
  created() {
    this.init();
  },
  methods: {
    ...mapActions({
      loadingStart: "loadingBar/start",
      loadingStop: "loadingBar/stop",
      flashMessage: "flashMessage/showFlashMessage",
      setCode: "error/setCode"
    }),
    async init() {
      this.loadingStart();
      this.clearError();
      const res_category = await axios.post(`/api/admin/category`);
      if (res_category.status !== OK) {
        this.setCode(res_category.status);
        return false;
      }
      this.arrayCategory = res_category.data;
      this.formDatas.title = [];
      this.formDatas.text = [];
      this.formDatas.category = [];
      this.arrayMode = ["register"];
      this.loadingStop();
    },
    mode(event) {
      this.validation(event);
    },
    async validation(mode) {
      this.loadingStart();
      const response = await axios.post(
        `/api/admin/news/validation`,
        this.formDatas
      );
      this.loadingStop();
      if (response.status !== OK) {
        this.formErrors = response.data.errors;
        this.setCode(response.status);
        this.flashMessage(MESSAGE_ERR);
        return false;
      }
      this.clearError();
      this.modal(mode); //mixins
    },
    async register() {
      this.hideDialog(); //mixins
      this.loadingStart();
      const response = await axios.post(
        `/api/admin/news/register`,
        this.formDatas
      );
      if (response.status !== OK) {
        this.setCode(response.status);
        return false;
      }
      //await this.$store.dispatch("categorys/categorys");
      this.flashMessage(MESSAGE_CREATE);
      this.$router.push(`/admin/news/edit/${response.data}`);
    },
    clearError() {
      this.formErrors = {};
    }
  },
  mixins: [DialogMixin]
};
</script>
