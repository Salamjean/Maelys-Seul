<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->enum('type_bien', ['appartement', 'maison', 'bureau']);
            $table->enum('typologie', ['studio', 'f1', 'f2', 'f3', 'f4', 'f5_plus', 'duplex', 'triplex', 'villa', 'chambre_salon', 'autre']);
            $table->decimal('superficie', 8, 2);
            $table->string('commune');
            $table->unsignedTinyInteger('nb_pieces')->default(1);
            $table->unsignedTinyInteger('nb_toilettes')->default(1);
            $table->boolean('garage')->default(false);
            $table->string('type_utilisation')->default('location');
            $table->decimal('loyer_mensuel', 12, 2);
            $table->unsignedTinyInteger('avance')->default(0);
            $table->unsignedTinyInteger('caution')->default(0);
            $table->unsignedTinyInteger('frais_agence')->default(0);
            $table->decimal('montant_total', 12, 2)->default(0);
            $table->unsignedTinyInteger('date_paiement')->nullable()->comment('Jour du mois (1-31)');
            $table->string('photo_principale');
            $table->text('google_maps_url')->nullable();
            $table->json('photos_supplementaires')->nullable();
            $table->text('video_3d')->nullable();
            $table->text('description')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'loue'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
