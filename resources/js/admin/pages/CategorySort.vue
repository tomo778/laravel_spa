<template>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">カテゴリ並び替え</h1>
    </div>

    <form>
      <p>ドラッグ&ドロップで並び替えできます。</p>
      <ol class="list-group" style="max-width: 400px;">
        <li
          v-for="(list, index) in lists"
          :key="list.id"
          draggable
          @dragstart="dragList($event, index)"
          @drop="dropList($event, index)"
          @dragover.prevent
          @dragenter.prevent
          class="list-group-item"
        >{{list.title}}</li>
      </ol>
      <footer class="fixed-bottom bg-white p-2 text-center">
        <button @click.prevent="show" class="btn btn-primary edit_btn">並び替える</button>
      </footer>
    </form>
    <v-dialog />
  </main>
</template>

<script>
import { MESSAGE_ERR, MESSAGE_CREATE, MESSAGE_UPDATE, OK } from "../util";
import draggable from "vuedraggable";

export default {
  components: {
    draggable
  },
  data() {
    return {
      lists: {
        sort_num: "",
        title: "",
        id: ""
      }
    };
  },
  methods: {
    show() {
      this.$modal.show("dialog", {
        title: "確認!",
        text: "本当に宜しいでしょうか？",
        buttons: [
          {
            title: "OK",
            handler: () => {
              this.update();
            }
          },
          // {
          //   title: "", // Button title
          //   default: true, // Will be triggered by default if 'Enter' pressed.
          //   handler: () => {} // Button click handler
          // },
          {
            title: "Close"
          }
        ]
      });
    },
    async update() {
      this.$modal.hide("dialog");
      this.$store.commit("loading/setLoading", true);
      const response = await axios.post(`/api/admin/category/sort`, this.lists);
      this.$store.commit("loading/setLoading", false);
      if (response.status !== OK) {
        this.$store.commit("error/setCode", response.status);
        this.$store.commit("message/setContent", {
          content: MESSAGE_ERR
        });
        return false;
      } else {
        this.$store.commit("message/setContent", {
          content: MESSAGE_UPDATE
        });
      }
    },
    dragList(event, dragIndex) {
      event.dataTransfer.effectAllowed = "move";
      event.dataTransfer.dropEffect = "move";
      event.dataTransfer.setData("drag-index", dragIndex);
    },
    dropList(event, dropIndex) {
      const dragIndex = event.dataTransfer.getData("drag-index");
      const deleteList = this.lists.splice(dragIndex, 1);
      this.lists.splice(dropIndex, 0, deleteList[0]);
      this.lists.map((list, index) => {
        list.sort_num = index + 1;
      });
    },
    async init() {
      this.$store.commit("loading/setLoading", true);
      const response2 = await axios.post(`/api/admin/category`);
      if (response2.status !== OK) {
        this.$store.commit("error/setCode", response2.status);
        return false;
      }
      this.lists = response2.data;
      this.$store.commit("loading/setLoading", false);
    }
  },
  created() {
    this.init();
  }
};
</script>
