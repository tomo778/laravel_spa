<template>
  <div class="form-row">
    <div class="form-group col-md-2">
      <label for="inputState">チェックボックス操作</label>
      <select v-model="selectedVal" v-on:change="checkbox" id="inputState" class="form-control">
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
  </div>
</template>

<script>
export default {
  props: {
    config: {
      type: Object
    }
  },
  data() {
    return { selectedVal: "" };
  },
  methods: {
    checkbox() {
      this.$emit("checkbox", this.selectedVal);
      this.selectedVal = "";
    }
  }
};
</script>
