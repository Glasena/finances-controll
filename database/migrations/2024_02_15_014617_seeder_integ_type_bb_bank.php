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
                'name' => 'Banco do Brasil',
                'code' => '001',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        DB::table('integration_types')->insert([
            [
                'description' => 'CSV - Banco do Brasil',
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
        DB::table('banks')->where('code', '001')->delete();
        DB::table('integration_types')->where('id', '2')->delete();
    }
};
