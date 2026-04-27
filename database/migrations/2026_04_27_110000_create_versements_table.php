<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('admins')->onDelete('cascade');
            $table->foreignId('comptable_id')->constrained('admins')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('reference')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
