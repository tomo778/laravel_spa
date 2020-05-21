<template>
  <component v-bind:is="layout" />
</template>

<script>
import IndexLayout from "./layout/IndexLayout.vue";
import MypageLayout from "./layout/MypageLayout.vue";

export default {
  components: {
    IndexLayout,
    MypageLayout
  },
  computed: {
    layout: {
      cache: false,
      get: function() {
        return (this.$route.meta.layout || "Default") + 'Layout';
      }
    }
  }
};
</script>
