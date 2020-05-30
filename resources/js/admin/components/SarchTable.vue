<template>
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
</template>

<script>
export default {
  props: {
    footer_mode: {
      type: Array,
      required: true
    }
  },
  methods: {
    mode(v) {
      this.$emit("mode", v);
    }
  }
};
</script>