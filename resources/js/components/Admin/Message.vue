<template>
  <div class="message fixed-top w-50 mt-3 ml-5" v-show="message">
    <div v-if="this.type == 1" class="alert alert-success">
      <strong>Success!</strong>
      {{ message }}
    </div>
    <div v-if="this.type == 2" class="alert alert-info">
      <strong>Info!</strong> {{ message }}
    </div>
    <div v-if="this.type == 3" class="alert alert-warning">
      <strong>Warning!</strong> {{ message }}
    </div>
    <div v-if="this.type == 4" class="alert alert-danger">
      <strong>Danger!</strong> {{ message }}
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  computed: {
    ...mapState({
      message: state => state.message.content,
      type: state => state.message.type
    })
  }
};
</script>
