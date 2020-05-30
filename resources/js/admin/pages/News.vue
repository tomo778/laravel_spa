<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">一覧</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <!-- <div class="btn-group mr-2">
          <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
          <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>-->
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
          <span data-feather="calendar"></span>
          検索
        </button>
      </div>
    </div>
    <table class="table border-bottom sarch-table">
      <tr>
        <th>フリーワード検索</th>
        <td>
          <input
            type="text"
            v-model="searchForm.freeword"
            class="form-control"
            placeholder="スペース区切りで検索できます（enterで検索）"
            required
            @keydown.enter="sarch"
          />
        </td>
      </tr>
      <tr>
        <th>公開状態</th>
        <td>
          <div>
            <input
              type="radio"
              v-bind:value="0"
              v-model="searchForm.status"
              id="all"
              checked
              v-on:change="sarch"
            />
            <label for="all">すべて</label>
          </div>
          <div v-for="(item, index) in config.array_status" :key="index">
            <input
              type="radio"
              v-bind:value="index"
              v-model="searchForm.status"
              :id="index"
              v-on:change="sarch"
            />
            <label :for="index">{{item}}</label>
          </div>
        </td>
      </tr>
    </table>
    <div v-if="pagination.total == 0">
      <p>条件にあうデータはありませんでした。</p>
    </div>
    <div v-if="pagination.total != 0">
      <Pagination :data="pagination"></Pagination>
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="inputState">チェックボックス操作</label>
          <select v-model="selected" @change="selectbox" id="inputState" class="form-control">
            <option value></option>
            <option
              v-for="(item, index) in config.array_status"
              :key="index"
              v-bind:value="index"
            >{{item}}にする</option>
            <option disabled>-----------</option>
            <option value="9">削除する</option>
          </select>
        </div>
        <div class="form-group col-md-4"></div>
        <div class="form-group col-md-6"></div>
      </div>
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
              <td width="200">{{ news.title }}</td>
              <td width="1500">{{ news.text}}</td>
              <td width="200">
                <div v-for="news in news.add_category" :key="news.id">
                  {{news.title}}
                  <hr />
                </div>
              </td>
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
import { OK, MESSAGE_UPDATE } from "../util";
import Pagination from "../components/Pagination.vue";
import SelectMixin from "../mixins/SelectMixin";

export default {
  components: {
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
      selected: [],
      isAllSelected: false,
      selectedCatIds: [],
      result: [],
      config: [],
      searchForm: {
        status: "0",
        freeword: ""
      }
    };
  },
  watch: {
    $route: {
      async handler() {
        await this.init();
      },
      immediate: true
    }
  },
  created() {
    this.init();
  },
  methods: {
    init() {
      this.config = this.$store.getters["config/config"];
      this.sarch();
    },
    async sarch() {
      this.$store.commit("loadingBar/start");
      let response = await axios.post(
        `/api/admin/${this.pluginName}/sarch?page=${this.page}`,
        this.searchForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      //page=1以外の時検索され、データが0の場合
      //page=1に戻す
      if (response.data.data.length == 0) {
        response = await axios.post(
          `/api/admin/${this.pluginName}/sarch?page=1`,
          this.searchForm
        );
        if (response.status !== OK) {
          this.$store.commit("error/setCode", response.status);
          return false;
        }
      }
      //
      this.selectedCatIds = [];
      this.isAllSelected = false;
      this.selected = "";
      //
      this.pagination = response.data;
      this.result = response.data.data;
      this.$store.commit("loadingBar/stop");
    }
  },
  mixins: [SelectMixin]
};
</script>
