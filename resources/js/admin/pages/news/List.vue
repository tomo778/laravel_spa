<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div class="pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">ニュース一覧</h1>
    </div>
    <SearchForm :formSearch="formSearch" :config="config" @sarch="sarch" />
    <div v-if="pagination.total == 0">
      <p>条件にあうデータはありませんでした。</p>
    </div>
    <div v-if="pagination.total != 0">
      <Pagination :data="pagination"></Pagination>
      <checkbox :config="config" @checkbox="checkbox" />
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>
                <input
                  type="checkbox"
                  id="checked_one"
                  v-model="isAllSelected"
                  @click="selectAllCats"
                />
              </th>
              <th>id</th>
              <th>状態</th>
              <th>タイトル</th>
              <th>本文</th>
              <th>カテゴリ</th>
              <th>登録</th>
              <th>更新</th>
              <th></th>
            </tr>
          </thead>
          <tbody v-for="news in result" :key="news.id">
            <tr>
              <th width="100">
                <input type="checkbox" :value="news.id" id="checkbox" v-model="selectedCatIds" />
              </th>
              <td width="100">{{ news.id }}</td>
              <td width="100">
                <span
                  v-if="news.status == config.STATUS_ON"
                  class="badge badge-primary"
                >{{ config.array_status[news.status] }}</span>
                <span
                  v-if="news.status == config.STATUS_OFF"
                  class="badge badge-secondary"
                >{{ config.array_status[news.status] }}</span>
              </td>
              <td width="200">{{ news.title | truncate(50, '...') }}</td>
              <td width="1500">{{ news.text | truncate(50, '...') }}</td>
              <td width="200">
                <div v-for="news in news.add_category" :key="news.id">
                  ・{{news.title}}
                  <br />
                </div>
              </td>
              <td class="text-nowrap">{{ news.created_at | moment_format("LLL")}}</td>
              <td class="text-nowrap">{{ news.updated_at | moment_format("LLL")}}</td>
              <td width="100">
                <RouterLink
                  class="btn btn-primary text-nowrap"
                  :to="`/admin/news/edit/${news.id}`"
                >更新</RouterLink>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :data="pagination"></Pagination>
    </div>
  </main>
</template>

<script>
import { OK, MESSAGE_UPDATE } from "../../util";
import Pagination from "../../components/Pagination.vue";
import SelectMixin from "../../mixins/SelectMixin";
import SearchForm from "./components/SearchForm.vue";
import Checkbox from "./components/Checkbox.vue";
import { mapGetters, mapActions } from "vuex";

export default {
  components: {
    Checkbox,
    SearchForm,
    Pagination
  },
  props: {
    page: {
      type: String,
      required: false,
      default: "1"
    }
  },
  data() {
    return {
      pluginName: "news",
      pagination: [],
      isAllSelected: false,
      selectedCatIds: [],
      result: [],
      formSearch: {
        status: "0",
        freeword: ""
      }
    };
  },
  computed: {
    ...mapGetters({
      config: "config/config",
    })
  },
  watch: {
    $route: {
      async handler() {
        await this.sarch();
      },
      immediate: true
    }
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
    init() {
      this.sarch();
    },
    async sarch() {
      this.loadingStart();
      let response = await axios.post(
        `/api/admin/${this.pluginName}/sarch?page=${this.page}`,
        this.formSearch
      );
      if (response.status !== OK) {
        this.setCode(response.status);
        return false;
      }
      //page=1以外の時検索されてデータが0の場合
      //page=1に戻す
      if (response.data.data.length == 0) {
        this.page = 1;
        //get値削除
        history.pushState(null, null, "/admin/news/");
        response = await axios.post(
          `/api/admin/${this.pluginName}/sarch?page=${this.page}`,
          this.formSearch
        );
        if (response.status !== OK) {
          this.setCode(response.status);
          return false;
        }
      }
      //
      this.selectedCatIds = [];
      this.isAllSelected = false;
      //
      this.pagination = response.data;
      this.result = response.data.data;
      this.loadingStop();
    },
    async checkbox(selectedVal) {
      if (!confirm("本当にいいですか？")) {
        return;
      }
      this.loadingStart();
      const response = await axios.post(`/api/admin/${this.pluginName}/selectbox`, {
        mode: selectedVal,
        vals: this.selectedCatIds
      });
      if (response.status !== OK) {
        this.setCode(response.status);
        return false;
      }
      this.sarch();
      this.flashMessage(MESSAGE_UPDATE);
    }
  },
  mixins: [SelectMixin]
};
</script>
