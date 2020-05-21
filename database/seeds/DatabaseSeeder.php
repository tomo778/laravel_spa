<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Admin;
use App\Category;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('UserTableSeeder');
        $this->call('AdminTableSeeder');
        $this->call('CategoryTableSeeder');
        $this->call('NewsTableSeeder');
        $this->call('CategoryRelTableSeeder');

        Model::reguard();
    }
}

class UserTableSeeder extends Seeder
{

    public function run()
    {
        //DB::table('users')->truncate(); //最初に全件削除

        User::create([
            'id' => '1',
            'name' => 'aaa',
            'email' => 'aaa@gmail.com',
            'password' => password_hash('aaa', PASSWORD_DEFAULT)
        ]);
        User::create([
            'id' => '2',
            'name' => 'bbb',
            'email' => 'bbb@gmail.com',
            'password' => password_hash('bbb', PASSWORD_DEFAULT)
        ]);
    }
}

class AdminTableSeeder extends Seeder
{

    public function run()
    {
        //DB::table('admin')->truncate(); //最初に全件削除

        Admin::create([
            'id' => '1',
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => password_hash('admin', PASSWORD_DEFAULT)
        ]);
        Admin::create([
            'id' => '2',
            'name' => 'aaa',
            'email' => 'aaa@gmail.com',
            'password' => password_hash('aaa', PASSWORD_DEFAULT)
        ]);
    }
}

class CategoryTableSeeder extends Seeder
{

    public function run()
    {
        //DB::table('category')->truncate(); //最初に全件削除

        Category::create([
            'id' => '1',
            'title' => 'it分野',
            'text' => 'it分野it分野it分野it分野it分野it分野の説明',
        ]);
        Category::create([
            'id' => '2',
            'title' => '経済',
            'text' => '経済カテゴリ経済カテゴリ経済カテゴリ経済カテゴリの説明',
        ]);
        Category::create([
            'id' => '3',
            'title' => '政治',
            'text' => '政治カテゴリ政治カテゴリ政治カテゴリ政治カテゴリの説明',
        ]);
    }
}

class NewsTableSeeder extends Seeder
{

    public function run()
    {
        //DB::table('news')->truncate(); //最初に全件削除
        $now = \Carbon\Carbon::now();
        for ($i = 1; $i < 333; $i++) {
            $data = [
                'status' => 1,
                'title' => 'newsタイトルnewsタイトル' . $i,
                'text' => 'news説明文news説明文news説明文news説明文news
                説明文news説明文news説明文news説明文news説明文news説明文
                news説明文news説明文news説明文news説明文news説明文news',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('news')->insert($data);
        }
        $time = $now->subYear();
        for ($i = 333; $i < 666; $i++) {
            $data = [
                'status' => 1,
                'title' => 'newsタイトルnewsタイトル' . $i,
                'text' => 'news説明文news説明文news説明文news説明文news
                説明文news説明文news説明文news説明文news説明文news説明文
                news説明文news説明文news説明文news説明文news説明文news',
                'created_at' => $time,
                'updated_at' => $time,
            ];
            DB::table('news')->insert($data);
        }
        $time = $now->subYear(3);

        for ($i = 666; $i < 1000; $i++) {
            $data = [
                'status' => 1,
                'title' => 'newsタイトルnewsタイトル' . $i,
                'text' => 'news説明文news説明文news説明文news説明文news
                説明文news説明文news説明文news説明文news説明文news説明文
                news説明文news説明文news説明文news説明文news説明文news',
                'created_at' => $time,
                'updated_at' => $time,
            ];
            DB::table('news')->insert($data);
        }
    }
}

class CategoryRelTableSeeder extends Seeder
{

    public function run()
    {
        //DB::table('category_rel')->truncate(); //最初に全件削除
        $now = \Carbon\Carbon::now();
        for ($i = 1; $i < 333; $i++) {
            $data = [
                'news_id' => $i,
                'category_id' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('category_rel')->insert($data);
        }
        $now = \Carbon\Carbon::now();
        for ($i = 333; $i < 666; $i++) {
            $data = [
                'news_id' => $i,
                'category_id' => '2',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('category_rel')->insert($data);
        }
        $now = \Carbon\Carbon::now();
        for ($i = 666; $i < 1000; $i++) {
            $data = [
                'news_id' => $i,
                'category_id' => '3',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            DB::table('category_rel')->insert($data);
        }
    }
}
