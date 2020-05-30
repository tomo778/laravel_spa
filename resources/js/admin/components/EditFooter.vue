<template>
  <footer class="fixed-bottom bg-white p-2 text-center border-top">
    <span v-for="v in arrayMode" :key="v">
      <button
        v-if="v == 'register'"
        @click.prevent="mode('register')"
        class="btn btn-primary edit_btn"
      >登録する</button>
      <button
        v-if="v == 'update'"
        @click.prevent="mode('update')"
        class="btn btn-primary edit_btn"
      >更新する</button>
      <button
        v-if="v == 'delete'"
        @click.prevent="mode('delete')"
        class="btn btn-secondary edit_btn"
      >削除する</button>
      <button
        v-if="v == 'sort'"
        @click.prevent="mode('sort')"
        class="btn btn-secondary edit_btn"
      >並び変える</button>
    </span>
  </footer>
</template>

<script>
export default {
  props: {
    arrayMode: {
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