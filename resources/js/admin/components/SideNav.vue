<template>
  <nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
      <ul class="nav flex-column">
        <li class="nav-item">
          <RouterLink class="nav-link" to="/admin">
            <span data-feather="home"></span>
            Dashboard
          </RouterLink>
          <hr />
        </li>
        <li v-for="plugin in plugins" :key="plugin.plugin" class="nav-item">
          <a class="nav-link" href="#">
            <span :data-feather="plugin.feather"></span>
            {{plugin.name}}
          </a>
          <ul>
            <li v-for="child in plugin.child" :key="child.plugin">
              <RouterLink class="nav-link" :to="{ path: child.path}">{{child.name}}</RouterLink>
            </li>
          </ul>
          <hr />
        </li>
      </ul>

      <!-- <ul class="nav flex-column mb-2">
        <li class="nav-item">
          <a class="nav-link" href="#">
            <span data-feather="home"></span>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <span data-feather="shopping-cart"></span>
            Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <span data-feather="users"></span>
            Customers
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <span data-feather="bar-chart-2"></span>
            Reports
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <span data-feather="layers"></span>
            Integrations
          </a>
        </li>
      </ul>-->
    </div>
  </nav>
</template>

<script>
import { plugins } from "../util";
export default {
  data() {
    return {
      plugins
    };
  }
};
</script>