<template>
  <aside class="col-md-4 blog-sidebar">
    <div class="p-3 mb-3 bg-light rounded">
      <h4 class="font-italic">About</h4>
      <p class="mb-0">
        サイトの説明サイトの説明サイトの説明サイトの説明サイトの説明サイトの説明
        サイトの説明サイトの説明サイトの説明サイトの説明
        サイトの説明サイトの説明サイトの説明
      </p>
    </div>

    <div class="p-3">
      <h4 class="font-italic">Categorys</h4>
      <ol class="list-unstyled mb-0">
        <li v-for="category in categorys" :key="category.id">
          <RouterLink :to="`/category/${category.id}`">{{category.title}}</RouterLink>
        </li>
      </ol>
    </div>
    <div class="p-3">
      <h4 class="font-italic">Archives</h4>
      <ul class="list-unstyled mb-0">
        <li v-for="(archive, key) in archives" :key="archive.id">
          <RouterLink :to="`/archive/${archive.year}/${archive.month}`">{{archive.year}}年{{archive.month}}月</RouterLink>({{archive.post_count}})
          <!-- <ul class="list-unstyled mb-0">
            <li v-for="(v, key) in archive" :key="v.id">
              <RouterLink :to="`/category/${v.month}`">{{v.month}}月</RouterLink>({{v.post_count}})
            </li>
          </ul> -->
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
export default {
  props: ["categorys", "archives"]
};
</script>