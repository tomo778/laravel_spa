<template>
  <div v-show="$store.state.loading.loading" class="fullview text-center">
    <div class="loading-spacer"></div>
    <div class="spinner-border text-secondary" style="width: 5rem; height: 5rem;" role="status"></div>
    <!-- <hr />
    <span>Loading...</span> -->
  </div>
</template>

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
.loading-spacer {
  height: 40%;
}
</style>