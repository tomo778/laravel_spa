<template>
  <div class="vld-parent">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
  </div>
</template>
<script>
// Import component
import Loading from "vue-loading-overlay";
// Import stylesheet
import "vue-loading-overlay/dist/vue-loading.css";
export default {
  components: {
    loading
  },
  data() {
    return {
      isLoading: false,
      fullPage: true
    };
  }
};
</script>