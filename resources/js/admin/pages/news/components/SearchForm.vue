<template>
  <table class="table border-bottom sarch-table">
    <tr>
      <th>フリーワード検索</th>
      <td>
        <input
          type="text"
          v-model="formSearch.freeword"
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
        <span class="mr-4">
          <input
            type="radio"
            v-bind:value="0"
            v-model="formSearch.status"
            id="all"
            checked
            v-on:change="sarch"
          />
          <label for="all">すべて</label>
        </span>
        <span  class="mr-4" v-for="(item, index) in config.array_status" :key="index">
          <input
            type="radio"
            v-bind:value="index"
            v-model="formSearch.status"
            :id="index"
            v-on:change="sarch"
          />
          <label :for="index">{{item}}</label>
        </span>
      </td>
    </tr>
  </table>
</template>

<script>
export default {
  props: {
    config: {
      type: Object
    },
    formSearch: {
      type: Object
    }
  },
  methods: {
    sarch() {
      this.$emit("sarch");
    }
  }
};
</script>
