<template>
  <div v-show="loading">
    <div class="fullview">
      <div class="loading-spacer"></div>
      <vue-loading 
        type="spin"
        color="#999"
        :size="{ width: '100px', height: '100px' }"
        >
      </vue-loading>
    </div>
  </div>
</template>

<script>
import { VueLoading } from 'vue-loading-template'

export default {
  name: 'loading',
  components: {
    VueLoading,
  },

}
</script>

<style>
.fullview {
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color:rgba(255,255,255,0.5);
  position: fixed;
  top: 0;
  left: 0;
}
.loading-spacer {
  height: 40%;
}
</style>