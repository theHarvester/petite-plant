<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);

        $article = new \App\Models\Article();

        $article->slug = 'foo-bar';
        $article->published_at = new DateTime();
        $article->setAttribute('content', '## I like apples');
        $article->save();


        $user = new \App\User();
        $user->email = 'example@gmail.com';
        $user->password = Hash::make('abc123');
        $user->save();
    }
}
