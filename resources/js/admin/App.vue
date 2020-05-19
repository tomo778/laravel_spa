<template>
  <component v-bind:is="layout" />
</template>

<script>
import DefaultLayout from "./layout/DefaultLayout.vue";
import LoginLayout from "./layout/LoginLayout.vue";
import IndexLayout from "./layout/IndexLayout.vue";

export default {
  components: {
    IndexLayout,
    DefaultLayout,
    LoginLayout
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
