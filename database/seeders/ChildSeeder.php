<?php
namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Seeder;

class ChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Child::factory(50)->create();
    }
}
