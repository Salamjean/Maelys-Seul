<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // On supprime les anciens (si la précédente migration a tourné)
            if (Schema::hasColumn('payments', 'start_period')) {
                $table->dropColumn(['start_period', 'end_period']);
            }
            $table->string('periode_couverte')->nullable()->after('months_count');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('periode_couverte');
        });
    }
};
