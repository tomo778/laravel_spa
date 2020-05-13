import Vue from 'vue'
import VueRouter from 'vue-router'

import Top from './pages/Top.vue'
//
import Login from './pages/admin/Login.vue'
import AdminTop from './pages/admin/Top.vue'
import AdminStaff from './pages/admin/Staff.vue'
import AdminStaffEdit from './pages/admin/StaffEdit.vue'
import AdminNews from './pages/admin/News.vue'
import AdminNewsEdit from './pages/admin/NewsEdit.vue'
import AdminCategory from './pages/admin/Category.vue'
import AdminCategoryEdit from './pages/admin/CategoryEdit.vue'
//
import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'
import store from './store'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)


const routes = [
  {
    path: '/',
    component: Top,
    //meta: { layout: "home", requiresGuest: true }
    // props: route => {
    //   const page = route.query.page
    //   return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    // }
  },
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
    path: '/admin/category/edit/:id',
    component: AdminCategoryEdit,
    props: true,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/login',
    component: Login,
    meta: { layout: "login",　requiresGuest: true }
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
      next('/')
    }
  } else if (to.matched.some(record => record.meta.requiresGuest)) {
    if (store.getters['auth/check']) {
      next('/')
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
