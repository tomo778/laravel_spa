import Vue from 'vue'
import VueRouter from 'vue-router'
//
import Index from './pages/Index.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Detail from './pages/Detail.vue'
import Category from './pages/Category.vue'
import Archive from './pages/Archive.vue'
import Mypage from './pages/Mypage.vue'
import MypageLikes from './pages/MypageLikes.vue'

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
    component: Index,
    props: route => {
      const page = route.query.p
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    }
  },
  {
    path: '/login',
    component: Login,
    meta: {requiresGuest: true }
  },
  {
    path: '/register',
    component: Register,
    meta: {requiresGuest: true }
  },
  {
    path: '/mypage',
    component: Mypage,
    meta: { layout: "MypageNav", requiresAuth: true }
  },
  {
    path: '/mypage/likes',
    component: MypageLikes,
    props: route => {
      return {
        page: route.query.p
      }
    },
    meta: { layout: "MypageNav", requiresAuth: true }
  },
  {
    path: '/detail/:id',
    props: true,
    component: Detail
  },
  {
    path: '/category/:id',
    props: route => {
      return {
        id: route.params['id'],
        page: route.query.p
      }
    },
    component: Category
  },
  {
    path: '/archive/:y/:m',
    props: route => {
      return {
        y: route.params['y'],
        m: route.params['m'],
        page: route.query.p
      }
    },
    component: Archive
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
