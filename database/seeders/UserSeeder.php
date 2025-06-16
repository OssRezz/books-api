<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Super admin',
            'email' => 'admin@books.com',
            'library_id' => '1036957215',
            'email_verified_at' => now(),
            'password' => '!Nana433550.',
        ]);

        $admin->assignRole('Admin');

        $client = User::create([
            'name' => 'Client user',
            'email' => 'client@books.com',
            'library_id' => '1036957216',
            'email_verified_at' => now(),
            'password' => '!Nana433550.',
        ]);

        $client->assignRole('Client');
    }
}
