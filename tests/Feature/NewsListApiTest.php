<?php

namespace Tests\Feature;

use App\News;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewsListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する()
    {
        // factory(Photo::class)->create()->each(function ($photo) {
        //     $photo->comments()->saveMany(factory(Comment::class, 3)->make());
        // });
        // $photo = Photo::first();

        // $response = $this->json('GET', route('photo.show', [
        //     'id' => $photo->id,
        // ]));

        // $response->assertStatus(200)
        //     ->assertJsonFragment([
        //         'id' => $photo->id,
        //         'url' => $photo->url,
        //         'owner' => [
        //             'name' => $photo->owner->name,
        //         ],
        //         'comments' => $photo->comments
        //             ->sortByDesc('id')
        //             ->map(function ($comment) {
        //                 return [
        //                     'author' => [
        //                         'name' => $comment->author->name,
        //                     ],
        //                     'content' => $comment->content,
        //                 ];
        //             })
        //             ->all(),
        //         'liked_by_user' => false,
        //         'likes_count' => 0,
        //     ]);
        // 5つの写真データを生成する
        factory(News::class, 5)->create();

        $response = $this->json('GET', route('index'));

        // 生成した写真データを作成日降順で取得
        $News_arr = News::with(['add_category'])->orderBy('created_at', 'desc')->get();

        // data項目の期待値
        $expected_data = $News_arr->map(function ($News) {
            return [
                'id' => $News->id,
                'status' => $News->url,
                'title' => $News->title,
                'text' => $News->text,
                'created_at' => $News->created_at,
                'updated_at' => $News->updated_at,
                'add_category' => [
                    'id' => $News->add_category->id,
                    'title' => $News->add_category->title,
                    'text' => $News->add_category->text,
                    'sort_num' => $News->add_category->sort_num,
                    'created_at' => $News->add_category->created_at,
                    'updated_at' => $News->add_category->updated_at,
                ],
            ];
        })
            ->all();

        $response->assertStatus(200)
            // レスポンスJSONのdata項目に含まれる要素が5つであること
            // ->assertJsonCount(5, 'data')
            // レスポンスJSONのdata項目が期待値と合致すること
            ->assertJsonFragment([
                "data" => $expected_data,
            ]);
    }
}
