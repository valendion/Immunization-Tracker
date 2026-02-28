<?php
namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data (lebih lambat tapi aman)
        Child::query()->delete();

        // Reset auto increment (opsional)
        DB::statement('ALTER TABLE children AUTO_INCREMENT = 1;');

        // Buat data baru
        Child::factory()->count(50)->create();
    }
}
