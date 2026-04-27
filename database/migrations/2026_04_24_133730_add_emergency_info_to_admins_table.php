<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('residence')->nullable()->after('role');
            $table->string('emergency_contact_name')->nullable()->after('residence');
            $table->string('emergency_contact')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['residence', 'emergency_contact_name', 'emergency_contact', 'emergency_contact_relation']);
        });
    }
};
