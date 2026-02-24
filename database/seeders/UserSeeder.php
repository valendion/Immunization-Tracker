<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name'     => 'Bang Operator',
                'email'    => 'operator@gmail.com',
                'role'     => 'operator',
                'password' => bcrypt('123456'),
            ],
            [
                'name'     => 'Bang admin',
                'email'    => 'admin@gmail.com',
                'role'     => 'admin',
                'password' => bcrypt('immunizationdion123'),
            ],
        ];

        foreach ($userData as $key => $value) {
            User::create($value);
        }

        // User::factory()->create([
        //     'name'  => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
