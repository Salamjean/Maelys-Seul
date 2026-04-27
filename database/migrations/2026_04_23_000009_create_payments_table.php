<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade');
            $blueprint->integer('months_count')->default(1);
            $blueprint->decimal('amount', 12, 2);
            $blueprint->string('payment_method')->default('especes');
            $blueprint->string('reference')->unique();
            $blueprint->string('verification_code')->nullable();
            $blueprint->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $blueprint->timestamp('paid_at')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
