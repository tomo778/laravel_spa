<template>
  <component v-bind:is="layout" />
</template>

<script>
import DefaultLayout from "./layout/DefaultLayout.vue";
import LoginLayout from "./layout/LoginLayout.vue";

export default {
  components: {
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
