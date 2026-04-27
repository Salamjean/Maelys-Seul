<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $blueprint) {
            $blueprint->string('qr_code')->nullable()->after('verification_code');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $blueprint) {
            $blueprint->dropColumn('qr_code');
        });
    }
};
