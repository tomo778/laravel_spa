export default {
  methods: {
    selectAllCats() {
      if (this.isAllSelected) {
        this.selectedCatIds = [];
        this.isAllSelected = false;
      } else {
        this.selectedCatIds = [];
        for (var cat in this.result) {
          this.selectedCatIds.push(this.result[cat].id);
        }
        this.isAllSelected = true;
      }
    },
    // select() {
    //   if (this.selectedCatIds.length !== this.result.length) {
    //     this.isAllSelected = false;
    //   } else {
    //     this.isAllSelected = true;
    //   }
    // }
  }
}