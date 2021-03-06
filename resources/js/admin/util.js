//各種レスポンスコード
export const OK = 200
export const CREATED = 201
export const INTERNAL_SERVER_ERROR = 500
export const UNPROCESSABLE_ENTITY = 422
export const UNAUTHORIZED = 419
export const NOT_FOUND = 404

//メッセージ
export const MESSAGE_ERR = { text: "エラーの箇所があります！", duration: 2000, mode: 'error' }
export const MESSAGE_CREATE = { text: "登録しました！", duration: 3000, mode: 'processing' }
export const MESSAGE_UPDATE = { text: "更新しました！", duration: 3000, mode: 'done' }
export const MESSAGE_DELETE = { text: "削除しました！", duration: 3000, mode: 'done' }
export const MESSAGE_LOGIN = { text: "ログインしました！", duration: 3000, mode: 'done' }
//
export const DIALOG_CREATE = { title: "確認!", text: 'データを作成します。宜しいでしょうか？' }
export const DIALOG_UPDATE = { title: "更新確認!", text: 'データを更新します。宜しいでしょうか？' }
export const DIALOG_DELETE = { title: "削除確認!", text: 'データを削除します。宜しいでしょうか？' }

// export const STATUS = {
//   1: "公開",
//   2: "非公開"
// }

//サイドバー配列
export const plugins = [
  {
    plugin: undefined,
    name: 'Dashboard',
    feather: 'home',
    child: [
      {
        name: 'トップ',
        path: '/admin',
      }
    ]
  },
  {
    plugin: 'staff',
    name: 'スタッフ',
    feather: 'users',
    child: [
      {
        name: '一覧',
        path: '/admin/staff',
      },
      {
        name: '登録・更新',
        path: '/admin/staff/edit',
      },
    ]
  },
  {
    plugin: 'news',
    name: 'ニュース',
    feather: 'file',
    child: [
      {
        name: '一覧',
        path: '/admin/news',
      },
      {
        name: '登録・更新',
        path: '/admin/news/edit',
      },
    ]
  },
  {
    plugin: 'category',
    name: 'カテゴリ',
    feather: 'link',
    child: [
      {
        name: '一覧',
        path: '/admin/category',
      },
      {
        name: '登録・更新',
        path: '/admin/category/edit',
      },
      {
        name: '並び替え',
        path: '/admin/category/sort',
      },
    ]
  },
  {
    plugin: 'user',
    name: 'ユーザー',
    feather: 'users',
    child: [
      {
        name: '一覧',
        path: '/admin/user',
      }
    ]
  }
]