<template>
  <div v-if="Errors" class="text-danger">
    <span>{{ mes }}</span>
  </div>
</template>

<script>
export default {
  props: {
    Errors: {
      type: Object,
      required: false,
      default: null
    },
    name: {
      type: String,
      required: true,
    }
  },
  computed: {
    mes() {      
      if (this.Errors[this.name]) {
        return this.Errors[this.name][0];
      }
    }
  }
};
</script>
