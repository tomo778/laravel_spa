<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\News;

class NewsListApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_トップページ_JSONチェック()
    {
        factory(News::class, 5)->create();
        $response = $this->json('GET', route('index'));
        $news = News::with('add_category')
            ->statusCheck()
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $expected_data = $news->map(function ($v) {
            return [
                'id' => $v->id,
                'status' => $v->status,
                'text' => $v->text,
                'title' => $v->title,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
                'likes_count' => 0,
                'liked_by_user' => false,
                'add_category' => [],
                'likes' => []
            ];
        })->all();
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment([
                "data" => $expected_data,
            ]);
    }
}
