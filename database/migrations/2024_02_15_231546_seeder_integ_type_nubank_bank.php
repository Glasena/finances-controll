<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('banks')->insert([
            [
                'name' => 'Nubank',
                'code' => '260',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('integration_types')->insert([
            [
                'description' => 'CSV - Nubank',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('banks')->where('code', '260')->delete();
        DB::table('integration_types')->where('id', '3')->delete();
    }
};
