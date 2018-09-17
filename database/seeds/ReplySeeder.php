<?php

use Illuminate\Database\Seeder;

class ReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Reply', 50)->create();
        factory('App\Channel', 8)->create();
    }
}
