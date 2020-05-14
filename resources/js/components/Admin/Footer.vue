<template>
  <footer class="fixed-bottom bg-white p-2 text-center">
    <button v-if="!this.id" @click.prevent="register" class="btn btn-primary edit_btn">登録する</button>
    <button v-if="!this.id" @click.prevent="update" class="btn btn-primary edit_btn">更新する</button>
  </footer>
</template>