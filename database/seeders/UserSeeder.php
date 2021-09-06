<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'user',
            'email' => 'user@net.com',
            'email_verified_at' => Carbon::now(),
            'is_admin' => 1,
            'password' => \Hash::make('Admin123'),
            'remember_token' => Str::random(10)
        ]);
    }
}
