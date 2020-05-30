<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">一覧</h1>
    </div>
    <div class="table-responsive">
      <table class="table text-nowrap">
        <thead>
          <tr>
            <th width="50">id</th>
            <th>タイトル</th>
            <th>説明文</th>
            <th width="50">更新</th>
          </tr>
        </thead>
        <tbody v-for="category in category_arr" :key="category.id">
          <tr>
            <td>{{ category.id }}</td>
            <td>{{ category.title }}</td>
            <td>{{ category.text}}</td>
            <td>
              <RouterLink class="btn btn-primary text-nowrap" :to="`/admin/category/edit/${category.id}`">更新</RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<script>
import { OK, STATUS, MESSAGE_UPDATE } from "../util";

export default {
  data() {
    return {
      category_arr: []
    };
  },
  created() {
    this.list();
  },
  methods: {
    async list() {
      this.$store.commit("loadingBar/start");
      const response = await axios.post(
        `/api/admin/category/list`
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.category_arr = response.data.data;
      this.$store.commit("loadingBar/stop");
    }
  }
};
</script>
