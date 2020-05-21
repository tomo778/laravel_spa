import Vue from 'vue'
import VueRouter from 'vue-router'
//
import Login from './pages/Login.vue'
import AdminTop from './pages/Top.vue'
import AdminStaff from './pages/Staff.vue'
import AdminStaffEdit from './pages/StaffEdit.vue'
import AdminNews from './pages/News.vue'
import AdminNewsEdit from './pages/NewsEdit.vue'
import AdminCategory from './pages/Category.vue'
import AdminCategoryEdit from './pages/CategoryEdit.vue'
import AdminCategorySort from './pages/CategorySort.vue'
import AdminUser from './pages/User.vue'

//
import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'
import store from './store'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

const routes = [
  {
    path: '/admin',
    component: AdminTop,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/staff',
    component: AdminStaff,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/staff/edit',
    component: AdminStaffEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/staff/edit/:id',
    component: AdminStaffEdit,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/news',
    component: AdminNews,
    props: route => {
      return {
        page: Number(route.query.p)
      }
    },
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/news/edit',
    component: AdminNewsEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/news/edit/:id',
    component: AdminNewsEdit,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/category',
    component: AdminCategory,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/category/edit',
    component: AdminCategoryEdit,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/category/sort',
    component: AdminCategorySort,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/category/edit/:id',
    component: AdminCategoryEdit,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/user',
    component: AdminUser,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/login',
    component: Login,
    meta: { layout: "login", requiresGuest: true }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '*',
    component: NotFound
  }
];


// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters['auth/check']) {
      next()
    } else {
      next('/admin/login')
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    if (store.getters['auth/check']) {
      next('/admin/login')
    } else {
      next()
    }
  } else {
    next();
  }
});

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
