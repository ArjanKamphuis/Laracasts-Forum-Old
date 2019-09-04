<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory('App\Thread', 10)->create()->each(function($thread) {
            factory('App\Reply', 5)->create(['thread_id' => $thread->id, 'user_id' => $thread->user_id]);
        });
    }
}
