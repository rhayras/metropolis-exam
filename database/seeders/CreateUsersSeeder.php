<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'Admin',
                'email' => 'admin@sample.com',
                'password' => bcrypt('Admin123'),
            ]
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
