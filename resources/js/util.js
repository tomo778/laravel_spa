/**
 * クッキーの値を取得する
 * @param {String} searchKey 検索するキー
 * @returns {String} キーに対応する値
 */
export function getCookieValue(searchKey) {
  if (typeof searchKey === 'undefined') {
    return ''
  }

  let val = ''

  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=')
    if (key === searchKey) {
      return val = value
    }
  })

  return val
}

//各種レスポンスコード
export const OK = 200
export const CREATED = 201
export const INTERNAL_SERVER_ERROR = 500
export const UNPROCESSABLE_ENTITY = 422
export const UNAUTHORIZED = 419
export const NOT_FOUND = 404

//メッセージ
export const MESSAGE_ERR = { mes: "エラーの箇所があります！", type: 4, }
export const MESSAGE_CREATE = { mes: "登録しました！", type: 1, }
export const MESSAGE_UPDATE = { mes: "更新しました！", type: 2, }

//form配列
// export const STATUS = [
//   { key: 1, value: "公開" },
//   { key: 2, value: "非公開" }
// ]

export const STATUS = {
  1: "公開",
  2: "非公開"
}

//サイドバー配列
export const plugins = [
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
      {
        name: 'カテゴリ',
        path: '/admin/category',
      },
      {
        name: 'カテゴリ登録・更新',
        path: '/admin/category/edit',
      },
    ]
  }
]