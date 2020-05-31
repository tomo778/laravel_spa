import {
  DIALOG_CREATE,
  DIALOG_UPDATE,
  DIALOG_DELETE,
} from "../util";

export default {
  data() {
    return {
      config: [],
      title: DIALOG_CREATE.title,
      text: DIALOG_CREATE.text
    };
  },
  methods: {
    modal(mode) {
      if (mode == "update") {
        this.title = DIALOG_UPDATE.title;
        this.text = DIALOG_UPDATE.text;
      }
      if (mode == "delete") {
        this.title = DIALOG_DELETE.title;
        this.text = DIALOG_DELETE.text;
      }
      this.$modal.show("dialog", {
        title: this.title,
        text: this.text,
        buttons: [
          {
            title: "OK",
            handler: () => {
              if (mode == "register") {
                this.register();
              }
              if (mode == "update") {
                this.update();
              }
              if (mode == "delete") {
                this.delete();
              }
            }
          },
          {
            title: "Close"
          }
        ]
      });
    },
    hideDialog() {
      this.$modal.hide("dialog");
    }
  }
}