<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('buku_tamus', 'jenis_buku')) {
            Schema::table('buku_tamus', function (Blueprint $table) {
                $table->enum('jenis_buku', ['digital', 'fisik'])->default('fisik')->after('telepon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('buku_tamus', 'jenis_buku')) {
            Schema::table('buku_tamus', function (Blueprint $table) {
                $table->dropColumn('jenis_buku');
            });
        }
    }
};
