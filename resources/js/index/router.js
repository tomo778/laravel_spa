import Vue from 'vue'
import VueRouter from 'vue-router'
//
import Index from './pages/Index.vue'
import Detail from './pages/Detail.vue'
import Category from './pages/Category.vue'
import Archive from './pages/Archive.vue'
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
    meta: { layout: "Index" },
    props: route => {
      const page = route.query.p
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    }
  },
  {
    path: '/detail/:id',
    props: true,
    component: Detail,
    meta: { layout: "Index" }
  },
  {
    path: '/category/:id',
    props: route => {
      return {
        id: route.params['id'],
        page: route.query.p
      }
    },
    component: Category,
    meta: { layout: "Index" }
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
    component: Archive,
    meta: { layout: "Index" }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '*',
    component: NotFound,
    meta: { layout: "Index" }
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

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router