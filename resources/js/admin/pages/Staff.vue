<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">一覧</h1>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>id</th>
            <th>name</th>
            <th>email</th>
            <th>更新</th>
          </tr>
        </thead>
        <tbody v-for="user in users" :key="user.id">
          <tr>
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <RouterLink class="btn btn-primary text-nowrap" :to="`/admin/staff/edit/${user.id}`">更新</RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>


<script>
import { OK } from "../util";

export default {
  data() {
    return {
      users: [],
      currentPage: 0,
      lastPage: 0,
      isLoading: false,
      fullPage: true
    };
  },
  methods: {
    async list() {
      this.$store.commit("loadingBar/start");
      const response = await axios.post(
        `/api/admin/user/list?page=${this.page}`
      );
      this.$store.commit("loadingBar/stop");
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.users = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
    }
  },
  created() {
    this.list();
  }
};
</script>
