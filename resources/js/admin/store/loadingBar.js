const state = {
  progressBar: {
    processing: false
  }
}

const mutations = {
  start({ progressBar }) {
    progressBar.processing = true;
  },
  stop({ progressBar }) {
    const dom = document.querySelector(".progress-bar");
    dom.style.animationPlayState = "paused";
    dom.style.animation = "none";
    dom.style.width = "100%";
    setTimeout(() => {
      progressBar.processing = false;
    }, 300);
  }
}

export default {
  namespaced: true,
  state,
  mutations
}
