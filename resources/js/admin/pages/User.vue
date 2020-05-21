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
            <th>名前</th>
            <th>email</th>
            <th>登録</th>
            <th>更新</th>
          </tr>
        </thead>
        <tbody v-for="user in user_arr" :key="user.id">
          <tr>
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email}}</td>
            <td>{{ user.created_at | moment }}</td>
            <td>{{ user.updated_at | moment}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
  </main>
</template>

<script>
import { OK, STATUS, MESSAGE_UPDATE } from "../util";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import moment from "moment";

export default {
  filters: {
    moment: function(date) {
      moment.locale("ja");
      return moment(date).format("LLL");
    }
  },
  components: {
    Loading
  },
  data() {
    return {
      user_arr: [],
      isLoading: false,
      fullPage: true,
    };
  },
  created() {
    this.list();
  },
  methods: {
    async list() {
      this.isLoading = true;
      const response = await axios.post(
        `/api/admin/user/list2`
      );
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        return false;
      }
      this.user_arr = response.data.data;
      this.isLoading = false;
    }
  }
};
</script>
