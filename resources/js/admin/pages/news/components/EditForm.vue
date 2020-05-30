<template>
  <table class="table admin-table">
    <tr v-if="formDatas.id">
      <th>id</th>
      <td>
        <div>{{formDatas.id}}</div>
        <input type="hidden" name="id" v-model="formDatas.id" />
      </td>
    </tr>
    <tr>
      <th>状態</th>
      <td>
        <div v-for="(item, index) in config.array_status" :key="index">
          <label>
            <input type="radio" v-bind:value="index" v-model="formDatas.status" />
            {{item}}
          </label>
        </div>
      </td>
    </tr>
    <tr>
      <th>カテゴリ</th>
      <td>
        <label class="mr-4" v-for="(item, index) in arrayCategory" :key="item.id">
          <input type="checkbox" v-bind:value="item.id" v-model="formDatas.category" />
          {{item.title}}
        </label>
        <ErrMessage :Errors="formErrors" :name="'category'" />
      </td>
    </tr>
    <tr>
      <th>タイトル</th>
      <td>
        <input type="text" class="form-control" name="title" v-model="formDatas.title" />
        <ErrMessage :Errors="formErrors" :name="'title'" />
      </td>
    </tr>
    <tr>
      <th>本文</th>
      <td>
        <textarea class="form-control" v-model="formDatas.text" cols="30" rows="10"></textarea>
        <ErrMessage :Errors="formErrors" :name="'text'" />
      </td>
    </tr>
  </table>
</template>

<script>
import ErrMessage from "../../../components/ErrMessage.vue";

export default {
  components: {
    ErrMessage
  },
  props: {
    formDatas: {
      type: Object
    },
    formErrors: {
      type: Object
    },
    arrayCategory: {
      type: Array
    },
    config: {
      type: Object
    },
  }
};
</script>
