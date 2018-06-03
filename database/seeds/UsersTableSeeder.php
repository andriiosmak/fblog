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
            'name'     => 'User A',
            'email'    => 'usera@fblog.com',
            'password' => bcrypt('secret'),
        ], [
            'name'     => 'User B',
            'email'    => 'userb@fblog.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
