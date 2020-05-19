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
    <div class="form-row">
      <div class="col-md-7 mb-3">
        <label for="validationTooltip03">フリーワード検索</label>
        <input
          type="text"
          v-model="searchForm.freeword"
          class="form-control"
          placeholder="スペース区切りで検索できます（enterで検索）"
          required
          @keydown.enter="sarch"
        />
        <br />
        <label for="validationTooltip03">公開状態</label>
        <div>
          <div class="custom-control custom-radio">
            <input
              type="radio"
              v-bind:value="0"
              v-model="searchForm.status"
              id="all"
              name="customRadio"
              class="custom-control-input"
              checked
              v-on:change="sarch"
            />
            <label class="custom-control-label" for="all">すべて</label>
          </div>
        </div>
        <div v-for="(item, index) in status" :key="index">
          <div class="custom-control custom-radio">
            <input
              type="radio"
              v-bind:value="index"
              v-model="searchForm.status"
              :id="index"
              name="customRadio"
              class="custom-control-input"
              v-on:change="sarch"
            />
            <label class="custom-control-label" :for="index">{{item}}</label>
          </div>
        </div>
      </div>
    </div>
    <!-- </form> -->
    <hr />
    <div v-if="items.total == 0">
      <p>条件にあうデータはありませんでした。</p>
    </div>
    <div v-if="items.total != 0">
      <Pagination :data="items"></Pagination>
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="inputState">チェックボックス操作</label>
          <select v-model="selected" @change="selectbox" id="inputState" class="form-control">
            <option value></option>
            <option value="1">公開</option>
            <option value="2">非公開</option>
            <option disabled>-----------</option>
            <option value="9">削除</option>
          </select>
        </div>
        <div class="form-group col-md-4"></div>
        <div class="form-group col-md-6"></div>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th width="50">
                <input
                  type="checkbox"
                  id="checked_one"
                  v-model="isAllSelected"
                  @click="selectAllCats"
                />
              </th>
              <th width="50">id</th>
              <th width="100">状態</th>
              <th>タイトル</th>
              <th>本文</th>
              <th>カテゴリ</th>
              <th width="50">更新</th>
            </tr>
          </thead>
          <tbody v-for="news in news_arr" :key="news.id">
            <tr>
              <th>
                <input type="checkbox" :value="news.id" id="checkbox" v-model="selectedCatIds" />
                <!-- <input type="checkbox" v-model="selectedCatIds" :value="news.id" @click="select" /> -->
              </th>
              <td>{{ news.id }}</td>
              <td>
                <span v-if="news.status == 1" class="badge badge-primary">{{ status[news.status] }}</span>
                <span
                  v-if="news.status == 2"
                  class="badge badge-secondary"
                >{{ status[news.status] }}</span>
              </td>
              <td>{{ news.title }}</td>
              <td>{{ news.text}}</td>
              <td>
                <div v-for="news in news.add_category" :key="news.id">{{news.title}}</div>
              </td>
              <td>
                <RouterLink class="edit" :to="`/admin/news/edit/${news.id}`">更新</RouterLink>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <Pagination :data="items"></Pagination>
      <Loading v-show="loading"></Loading>
    </div>
  </main>
</template>


<script>
import { OK, STATUS, MESSAGE_UPDATE } from "../../util";
import Pagination from "../../components/Admin/Pagination.vue";
import Loading from "../../components/Loading.vue";

export default {
  components: {
    Loading,
    Pagination
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1
    }
  },
  data() {
    return {
      items: [],
      freeword: "",
      selected: [],
      isAllSelected: false,
      selectedCatIds: [],
      status: STATUS,
      laravelData: {},
      news_arr: [],
      currentPage: 0,
      lastPage: 0,
      loading: false,
      fullPage: true,
      searchForm: {
        status: "0",
        freeword: ""
      }
    };
  },
  // mounted() {
  //   // Fetch initial results
  //   this.init();
  // },
  methods: {
    async init() {
      this.loading = true;
      const response = await axios.post(
        `/api/admin/news/sarch?page=${this.page}`,
        this.searchForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.items = response.data;
      this.news_arr = response.data.data;
      this.loading = false;
    },
    async sarch() {
      this.loading = true;
      const response = await axios.post(
        `/api/admin/news/sarch?page=1`,
        this.searchForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.items = response.data;
      this.news_arr = response.data.data;
      this.loading = false;
      this.$router.push("/admin/news");
    },
    selectAllCats() {
      if (this.isAllSelected) {
        this.selectedCatIds = [];
        this.isAllSelected = false;
      } else {
        this.selectedCatIds = [];
        for (var cat in this.news_arr) {
          this.selectedCatIds.push(this.news_arr[cat].id);
        }
        this.isAllSelected = true;
      }
    },
    select() {
      if (this.selectedCatIds.length !== this.news_arr.length) {
        this.isAllSelected = false;
      } else {
        this.isAllSelected = true;
      }
    },

    async selectbox() {
      if (!confirm("本当にいいですか？")) {
        this.selected = "";
        return;
      }
      const response = await axios.post("/api/admin/news/selectbox", {
        mode: this.selected,
        vals: this.selectedCatIds
      });
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch("categorys/categorys");
      //
      this.selected = "";
      this.init();
      this.$store.commit("message/setContent", {
        content: MESSAGE_UPDATE
      });
    }
  },
  created() {
    this.init();
  },
  watch: {
    $route: {
      async handler() {
        await this.init();
      },
      immediate: true
    }
  }
};
</script>
