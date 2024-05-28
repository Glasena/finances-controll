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
        DB::table('banks')->where('code', '001')->update(['img' => 'bb.svg']);
        DB::table('banks')->where('code', '260')->update(['img' => 'nubank.svg']);
        DB::table('banks')->where('code', '748')->update(['img' => 'sicredi.svg']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('banks')->where('code', '001')->update(['img' => null]);
        DB::table('banks')->where('code', '260')->update(['img' => null]);
        DB::table('banks')->where('code', '748')->update(['img' => null]);
    }
};
