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
        </div> -->
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
          <span data-feather="calendar"></span>
          検索
        </button>
      </div>
    </div>
    <form class="needs-validation" novalidate　@submit.prevent="sarch">
      <div class="form-row">
        <div class="col-md-7 mb-3">
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
              />
              <label class="custom-control-label" :for="index">{{item}}</label>
            </div>
          </div>
          <br />
          <label for="validationTooltip03">フリーワード検索</label>
          <input
            type="text"
            v-model="searchForm.freeword"
            class="form-control"
            placeholder="スペース区切りで検索できます"
            required
          />
          <br />
          <button class="btn btn-primary" type="submit">検索</button>
        </div>
      </div>
    </form>
    <hr />
    <!-- <h2>Section title</h2> -->
    <pagination :data="laravelData" @pagination-change-page="list"></pagination>
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
      <table class="table text-nowrap">
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
            <th>説明文</th>
            <th width="50">更新</th>
          </tr>
        </thead>
        <tbody v-for="category in category_arr" :key="category.id">
          <tr>
            <th>
              <input type="checkbox" :value="category.id" id="checkbox" v-model="selectedCatIds" />
              <!-- <input type="checkbox" v-model="selectedCatIds" :value="category.id" @click="select" /> -->
            </th>
            <td>{{ category.id }}</td>
            <td>
              <span v-if="category.status == 1" class="badge badge-primary">{{ status[category.status] }}</span>
              <span v-if="category.status == 2" class="badge badge-secondary">{{ status[category.status] }}</span>
            </td>
            <td>{{ category.title }}</td>
            <td>{{ category.text}}</td>
            <td>
              <RouterLink class="edit" :to="`/admin/category/edit/${category.id}`">更新</RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>

    <pagination :data="laravelData" @pagination-change-page="list"></pagination>
    <!-- <pagination :data="laravelData">
      <span slot="prev-nav">&lt; Previous</span>
      <span slot="next-nav">Next &gt;</span>
    </pagination>-->
  </main>
</template>


<script>
import { OK, STATUS, MESSAGE_UPDATE } from "../../util";
import Pagination from "laravel-vue-pagination";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

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
      freeword: "",
      selected: [],
      isAllSelected: false,
      selectedCatIds: [],
      status: STATUS,
      laravelData: {},
      category_arr: [],
      currentPage: 0,
      lastPage: 0,
      isLoading: false,
      fullPage: true,
      searchForm: {
        status: "0",
        freeword: ""
      }
    };
  },
  mounted() {
    // Fetch initial results
    this.list();
  },
  created() {
    this.list();
  },
  methods: {
    async list() {
      this.isLoading = true;
      const response = await axios.post(`/api/admin/category?page=${this.page}`);

      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.laravelData = response.data;
      this.category_arr = response.data.data;
      this.isLoading = false;
    },
    selectAllCats() {
      if (this.isAllSelected) {
        this.selectedCatIds = [];
        this.isAllSelected = false;
      } else {
        this.selectedCatIds = [];
        for (var cat in this.category_arr) {
          this.selectedCatIds.push(this.category_arr[cat].id);
        }
        this.isAllSelected = true;
      }
    },
    select() {
      if (this.selectedCatIds.length !== this.category_arr.length) {
        this.isAllSelected = false;
      } else {
        this.isAllSelected = true;
      }
    },
    async sarch() {
      this.isLoading = true;
      const response = await axios.post(
        "/api/admin/category/sarch",
        this.searchForm
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.laravelData = response.data;
      this.category_arr = response.data.data;
      this.isLoading = false;
    },
    async selectbox() {
      if (!confirm("本当にいいですか？")) {
        this.selected = "";
        return;
      }
      const response = await axios.post("/api/admin/category/selectbox", {
        mode: this.selected,
        vals: this.selectedCatIds
      });
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      //
      this.selected = "";
      this.sarch();
      this.$store.commit("message/setContent", {
        content: MESSAGE_UPDATE
      });
    }
  }
};
</script>
