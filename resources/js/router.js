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

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: Top
    // props: route => {
    //   const page = route.query.page
    //   return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    // }
  },
  {
    path: '/admin',
    component: AdminTop,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/staff',
    component: AdminStaff,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/staff/edit',
    component: AdminStaffEdit,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/staff/edit/:id',
    component: AdminStaffEdit,
    props: true,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/news',
    component: AdminNews,
    // props: route => {
    //   const page = route.query.page
    //   return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    // },
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/news/edit',
    component: AdminNewsEdit,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/news/edit/:id',
    component: AdminNewsEdit,
    props: true,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/category',
    component: AdminCategory,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/category/edit',
    component: AdminCategoryEdit,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/category/edit/:id',
    component: AdminCategoryEdit,
    props: true,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/admin/login',
    component: Login,
    beforeEnter(to, from, next) {
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '*',
    component: NotFound
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
