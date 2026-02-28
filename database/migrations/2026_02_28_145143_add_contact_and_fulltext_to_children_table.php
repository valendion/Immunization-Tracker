<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {

            $table->string('contact')->nullable()->after('parent_name');

            // Tambah full-text index
            $table->fullText(['nik', 'name', 'address', 'parent_name', 'contact']);
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropFullText(['nik', 'name', 'address', 'parent_name', 'contact']);
            $table->dropColumn('contact');
        });
    }
};
