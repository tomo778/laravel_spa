<template>
  <div class="fullview" v-if="isProcessing">
    <div class="progress-bar-bg fixed-top">
      <div class="progress-bar"></div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    isProcessing() {
      return this.$store.state.loadingBar.progressBar.processing;
    }
  }
};
</script>

<style>
.fullview {
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: rgba(255, 255, 255, 0.5);
  position: fixed;
  top: 0;
  left: 0;
}
.progress-bar-bg {
  width: 100%;
  height: 3px;
  background: #eee;
}
.progress-bar-bg .progress-bar {
  overflow: no-display;
  width: 0;
  height: 3px;
  background: #1496ed;
  background-position: 100px 100px;
  animation-name: moveIndeterminate;
  animation-duration: 5s;
  animation-timing-function: all 5s cubic-bezier(0.235, 0.285, 0.975, 0.055);
}

@keyframes moveIndeterminate {
  0% {
    width: 0;
  }
  10% {
    width: 15%;
  }
  100% {
    width: 100%;
  }
}
</style>