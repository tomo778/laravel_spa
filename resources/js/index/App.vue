<template>
  <component v-bind:is="layout" />
</template>

<script>
import IndexLayout from "./layout/IndexLayout.vue";

export default {
  components: {
    IndexLayout,
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
