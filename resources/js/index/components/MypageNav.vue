<template>
  <aside class="col-md-4 blog-sidebar">
    <div class="p-3 mb-3 bg-light rounded">
      <p class="mb-0">
        サイトの説明サイトの説明サイトの説明サイトの説明サイトの説明サイトの説明
        サイトの説明サイトの説明サイトの説明サイトの説明
        サイトの説明サイトの説明サイトの説明
      </p>
    </div>

    <div class="p-3">
      <h4 class="font-italic">メニュー</h4>
      <ol class="list-unstyled mb-0">
        <li>
          <RouterLink :to="`/mypage/`">マイページトップ</RouterLink>
        </li>
        <li>
          <RouterLink :to="`/mypage/likes`">お気に入り一覧</RouterLink>
        </li>
      </ol>
    </div>
  </aside>
</template>