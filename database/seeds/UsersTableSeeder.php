<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed users table.
     *
     * @return void
     */
    public function run()
    {
        DB::table(User::getTableName())->insert([
            'name'     => 'Test User',
            'email'    => 'user@fblog.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
