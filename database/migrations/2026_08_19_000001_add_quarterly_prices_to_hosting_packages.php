<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hosting_packages', function (Blueprint $table) {
            $table->decimal('price_quarterly', 10, 2)->default(0)->after('price_monthly');   // 3 months
            $table->decimal('price_semiannual', 10, 2)->default(0)->after('price_quarterly'); // 6 months
            $table->decimal('price_triennial', 10, 2)->default(0)->after('price_biennially'); // 36 months
        });
    }

    public function down(): void
    {
        Schema::table('hosting_packages', function (Blueprint $table) {
            $table->dropColumn(['price_quarterly', 'price_semiannual', 'price_triennial']);
        });
    }
};
