<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proprietaires', function (Blueprint $col) {
            $col->id();
            $col->string('nom');
            $col->string('prenoms');
            $col->string('email')->nullable();
            $col->string('contact');
            $col->string('lieu_residence')->nullable();
            $col->string('profession')->nullable();
            $col->string('piece_identite_recto')->nullable();
            $col->string('piece_identite_verso')->nullable();
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proprietaires');
    }
};
