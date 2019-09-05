<?php

use App\Channel;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        $user = User::create([
            'name' => 'JohnDoe',
            'email' => 'john@example.com',
            'password' => Hash::make('john1234'),
            'email_verified_at' => now()
        ]);

        $thread = factory('App\Thread')->create(['user_id' => $user->id]);
        factory('App\Reply', 2)->create(['thread_id' => $thread->id, 'user_id' => $user->id]);
    }
}
