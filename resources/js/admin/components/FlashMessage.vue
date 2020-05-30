<template>
  <div id="mes" class="fixed-top w-50 mt-4 ml-5" v-show="visible">
    <div class="alert" :class="outerClass">
      {{ this.$store.state.flashMessage.text }}
    </div>
  </div>
</template>

<style>
#mes {
  z-index:9998;
}
</style>

<script>
export default {
  computed: {
    mode: function() {
      return this.$store.state.flashMessage.mode;
    },
    visible: function() {
      return this.$store.state.flashMessage.visible;
    },
    outerClass: function() {
      return {
        "alert-success": this.mode === "processing",
        "alert-info": this.mode === "done",
        "alert-danger": this.mode === "error",
      };
    }
  }
};
</script>                   