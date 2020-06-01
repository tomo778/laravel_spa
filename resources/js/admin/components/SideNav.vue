<template>
  <nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
      <ul class="nav flex-column">
        <li v-for="(plugin, index) in plugins" :key="plugin.plugin" class="nav-item">
          <span class="nav-link" v-bind:class="{active: plugin.plugin === isActive}">
            <span :data-feather="plugin.feather"></span>
            {{plugin.name}}
          </span>
          <ul>
            <li
              v-for="(child, index_child) in plugin.child"
              :key="child.plugin"
            >
              <RouterLink class="nav-link" :to="{ path: child.path}">{{child.name}}</RouterLink>
            </li>
          </ul>
          <hr />
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { plugins } from "../util";
export default {
  data() {
    return {
      plugins,
      isActive: null
    };
  },
  watch: {
    $route: {
      async handler() {
        await this.Active();
      },
      immediate: true
    }
  },
  methods: {
    Active() {
      const words = window.location.pathname.split("/");
      this.isActive = words[2];
    }
  }
};
</script>